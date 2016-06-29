<?php

namespace yii\amqp;

use yii\amqp\helpers\ExceptionHelper;
use yii\amqp\json\JsonMessageDecodeStrategy;
use yii\amqp\json\JsonMessageEncodeStrategy;
use yii\amqp\raw\RawMessageDecodeStrategy;
use yii\amqp\raw\RawMessageEncodeStrategy;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * Class Amqp
 *
 * @package yii\amqp
 */
class Amqp extends Component
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

    protected static $classMap = [
        'raw' => [
            MessageEncodeStrategy::class => RawMessageEncodeStrategy::class,
            MessageDecodeStrategy::class => RawMessageDecodeStrategy::class,
        ],
        'json' => [
            MessageEncodeStrategy::class => JsonMessageEncodeStrategy::class,
            MessageDecodeStrategy::class => JsonMessageDecodeStrategy::class,
        ],
    ];

    /**
     * @var Configuration
     */
    public $configuration;

    /**
     * @var string
     */
    public $dataType = 'json';

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

        $classes = ArrayHelper::getValue(static::$classMap, $this->dataType);

        if (!$classes) {
            throw new \RuntimeException(\Yii::t('yii', 'Wrong data type ({dataType})',
                ['dataType' => $this->dataType]));
        }

        foreach ($classes as $interface => $class) {
            \Yii::$container->set($interface, $class);
        }

        $this->configuration = $this->configuration ?: new Configuration();
        $this->rawConnection = new \AMQPConnection($this->configuration->toAmqpCredentialsArray());

        try {
            $this->rawConnection->connect();
        } catch (\AMQPConnectionException $e) {
            ExceptionHelper::throwRightException($e);
        }
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
            ExceptionHelper::throwRightException($e);
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
            ExceptionHelper::throwRightException($e);
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
            ExceptionHelper::throwRightException($e);
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