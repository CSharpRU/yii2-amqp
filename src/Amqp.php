<?php

namespace yii\amqp;

use yii\amqp\client\Channel;
use yii\amqp\client\Client;
use yii\amqp\client\Exchange;
use yii\amqp\client\Queue;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Class Amqp
 *
 * @package yii\amqp
 */
class Amqp extends Component
{
    /**
     * @var Client
     */
    public $client;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $queueName;

    /**
     * @var string
     */
    public $routingKey = '';

    /**
     * @var int
     */
    public $queueFlags = Client::NOPARAM;

    /**
     * @var array
     */
    public $queueBindArguments = [];

    /**
     * @var string
     */
    public $exchangeName;

    /**
     * @var string
     */
    public $exchangeType = Client::EX_TYPE_DIRECT;

    /**
     * @var int
     */
    public $exchangeFlags = Client::NOPARAM;

    /**
     * @var string
     */
    public $messageType;

    /**
     * @var Channel
     */
    protected $channel;

    /**
     * @var Queue
     */
    protected $queue;

    /**
     * @var Exchange
     */
    protected $exchange;

    /**
     * @inheritDoc
     */
    public function __construct(Client $client, $config = [])
    {
        $this->client = $client;

        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        if (!$this->client) {
            throw new InvalidConfigException(\Yii::t('yii', 'AMQP client should be specified'));
        }

        if ($this->name) {
            $this->queueName = $this->queueName ?: $this->name;
            $this->exchangeName = $this->exchangeName ?: $this->name;
        }

        if (!$this->exchangeName) {
            throw new InvalidConfigException(\Yii::t('yii', 'Exchange name should be specified'));
        }

        if (!$this->queueName) {
            throw new InvalidConfigException(\Yii::t('yii', 'Queue name should be specified'));
        }

        if (!$this->client->isConnected()) {
            $this->client->connect();
        }

        $this->channel = $this->client->newChannel();
        $this->exchange = $this->client->newExchange($this->channel, $this->messageType);
        $this->queue = $this->client->newQueue($this->channel, $this->messageType);

        $this->exchange->setName($this->exchangeName);
        $this->exchange->setFlags($this->exchangeFlags);
        $this->exchange->setType($this->exchangeType);

        $this->exchange->declareExchange();

        $this->queue->setName($this->queueName);
        $this->queue->setFlags($this->queueFlags);

        $this->queue->declareQueue();
        $this->queue->bind($this->exchange, $this->routingKey, $this->queueBindArguments);
    }

    /**
     * @param mixed $message
     * @param int   $flags
     * @param array $attributes
     *
     * @return bool
     */
    public function publish($message, $flags = Client::NOPARAM, array $attributes = [])
    {
        $attributes = ArrayHelper::merge(['app_id' => \Yii::$app ? \Yii::$app->name : ''], $attributes);

        return $this->exchange->publish($message, $this->routingKey, $flags, $attributes);
    }

    /**
     * @param callable $callback
     * @param int      $flags
     * @param string   $consumerTag
     */
    public function consume(callable $callback, $flags = Client::NOPARAM, $consumerTag = '')
    {
        $this->queue->consume($callback, $flags, $consumerTag);
    }

    /**
     * @param string $deliveryTag
     * @param int    $flags
     *
     * @return bool
     */
    public function ack($deliveryTag, $flags = Client::NOPARAM)
    {
        return $this->queue->ack($deliveryTag, $flags);
    }

    /**
     * @param string $deliveryTag
     * @param int    $flags
     *
     * @return bool
     */
    public function nack($deliveryTag, $flags = Client::NOPARAM)
    {
        return $this->queue->nack($deliveryTag, $flags);
    }

    /**
     * @return bool
     */
    public function purge()
    {
        return $this->queue->purge();
    }
}