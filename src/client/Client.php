<?php

namespace yii\amqp\client;

use yii\amqp\client\strategies\JsonMessageEncodeDecodeStrategy;
use yii\amqp\client\strategies\MessageEncodeDecodeStrategy;
use yii\amqp\client\strategies\RawMessageEncodeDecodeStrategy;
use yii\amqp\client\strategies\SerializeMessageEncodeStrategy;
use yii\amqp\helpers\ClientHelper;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * Class Client
 *
 * @package yii\amqp
 */
class Client extends Component
{
    /** Map for full encapsulation */
    const NOPARAM = AMQP_NOPARAM;
    const DURABLE = AMQP_DURABLE;
    const PASSIVE = AMQP_PASSIVE;
    const EXCLUSIVE = AMQP_EXCLUSIVE;
    const AUTODELETE = AMQP_AUTODELETE;
    const INTERNAL = AMQP_INTERNAL;
    const NOLOCAL = AMQP_NOLOCAL;
    const AUTOACK = AMQP_AUTOACK;
    const IFEMPTY = AMQP_IFEMPTY;
    const IFUNUSED = AMQP_IFUNUSED;
    const MANDATORY = AMQP_MANDATORY;
    const IMMEDIATE = AMQP_IMMEDIATE;
    const MULTIPLE = AMQP_MULTIPLE;
    const NOWAIT = AMQP_NOWAIT;
    const REQUEUE = AMQP_REQUEUE;
    const EX_TYPE_DIRECT = AMQP_EX_TYPE_DIRECT;
    const EX_TYPE_FANOUT = AMQP_EX_TYPE_FANOUT;
    const EX_TYPE_TOPIC = AMQP_EX_TYPE_TOPIC;
    const EX_TYPE_HEADERS = AMQP_EX_TYPE_HEADERS;
    const OS_SOCKET_TIMEOUT_ERRNO = AMQP_OS_SOCKET_TIMEOUT_ERRNO;
    const MAX_CHANNELS = PHP_AMQP_MAX_CHANNELS;

    /**
     * Class map for DI container. Depends on message type.
     *
     * @see static::$messageType
     * @var array
     */
    protected static $classMap = [
        'raw' => [MessageEncodeDecodeStrategy::class => RawMessageEncodeDecodeStrategy::class],
        'json' => [MessageEncodeDecodeStrategy::class => JsonMessageEncodeDecodeStrategy::class],
        'serialize' => [MessageEncodeDecodeStrategy::class => SerializeMessageEncodeStrategy::class],
    ];

    /**
     * @var string
     */
    public $host = 'localhost';

    /**
     * @var string
     */
    public $virtualHost = '';

    /**
     * @var int
     */
    public $port = 5672;

    /**
     * @var string
     */
    public $login = 'guest';

    /**
     * @var string
     */
    public $password = 'guest';

    /**
     * @var int
     */
    public $readTimeout = 0;

    /**
     * @var int
     */
    public $writeTimeout = 0;

    /**
     * @var int
     */
    public $connectTimeout = 0;

    /**
     * @var string
     */
    public $messageType = 'serialize';

    /**
     * @var \AMQPConnection
     */
    protected $rawConnection;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->initContainer();

        $this->rawConnection = new \AMQPConnection($this->buildAmqpCredentialsArray());

        try {
            $this->rawConnection->connect();
        } catch (\AMQPConnectionException $e) {
            ClientHelper::throwRightException($e);
        }
    }

    private function initContainer()
    {
        if (\Yii::$container->has(MessageEncodeDecodeStrategy::class)) {
            return; // if it was added outside, just ignore our class map
        }

        $classes = ArrayHelper::getValue(static::$classMap, $this->messageType);

        if (!$classes) {
            throw new \RuntimeException(\Yii::t('yii', 'Wrong data type for message ({messageDataType})',
                ['messageDataType' => $this->messageType]));
        }

        foreach ($classes as $interface => $class) {
            \Yii::$container->set($interface, $class);
        }
    }

    /**
     * @return array
     */
    protected function buildAmqpCredentialsArray()
    {
        return [
            'host' => $this->host,
            'port' => $this->port,
            'vhost' => $this->virtualHost,
            'login' => $this->login,
            'password' => $this->password,
            'read_timeout' => $this->readTimeout,
            'write_timeout' => $this->writeTimeout,
            'connect_timeout' => $this->connectTimeout,
        ];
    }

    /**
     * @return Channel
     */
    public function newChannel()
    {
        try {
            return \Yii::createObject([
                'class' => Channel::class,
                'amqp' => $this,
            ]);
        } catch (\AMQPConnectionException $e) {
            ClientHelper::throwRightException($e);
        }
    }

    /**
     * @param Channel $channel
     *
     * @return Exchange
     */
    public function newExchange(Channel $channel)
    {
        try {
            return \Yii::createObject([
                'class' => Exchange::class,
                'channel' => $channel,
            ]);
        } catch (\Exception $e) {
            ClientHelper::throwRightException($e);
        }
    }

    /**
     * @param Channel $channel
     *
     * @return Queue
     */
    public function newQueue(Channel $channel)
    {
        try {
            return \Yii::createObject([
                'class' => Queue::class,
                'channel' => $channel,
            ]);
        } catch (\Exception $e) {
            ClientHelper::throwRightException($e);
        }
    }

    /**
     * @return \AMQPConnection
     */
    public function getRawConnection()
    {
        return $this->rawConnection;
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        return $this->rawConnection->isConnected();
    }
}