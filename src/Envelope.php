<?php

namespace yii\amqp;

use yii\base\Object;
use yii\helpers\ArrayHelper;

/**
 * Class Envelope
 *
 * @package yii\amqp
 */
class Envelope extends Object
{
    /**
     * @var mixed
     */
    public $body;

    /**
     * @var string
     */
    public $routingKey;

    /**
     * @var string
     */
    public $deliveryTag;

    /**
     * @var integer
     */
    public $deliveryMode;

    /**
     * @var string
     */
    public $exchangeName;

    /**
     * @var bool
     */
    public $redelivery;

    /**
     * @var string
     */
    public $contentType;

    /**
     * @var string
     */
    public $contentEncoding;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $timestamp;

    /**
     * @var int
     */
    public $priority;

    /**
     * @var string
     */
    public $expiration;

    /**
     * @var string
     */
    public $userId;

    /**
     * @var string
     */
    public $appId;

    /**
     * @var string
     */
    public $messageId;

    /**
     * @var string
     */
    public $replyTo;

    /**
     * @var string
     */
    public $correlationId;

    /**
     * @var array
     */
    public $headers;

    /**
     * @var MessageDecodeStrategy
     */
    protected $decodeStrategy;

    /**
     * @inheritDoc
     */
    public function __construct(MessageDecodeStrategy $decodeStrategy, $config = [])
    {
        $this->decodeStrategy = $decodeStrategy;

        parent::__construct($config);
    }

    /**
     * @param \AMQPEnvelope $message
     *
     * @return static
     */
    public static function createFromRaw(\AMQPEnvelope $message)
    {
        return \Yii::createObject([
            'class' => static::className(),
            'body' => $message->getBody(),
            'routingKey' => $message->getRoutingKey(),
            'deliveryTag' => $message->getDeliveryTag(),
            'deliveryMode' => $message->getDeliveryMode(),
            'exchangeName' => $message->getExchangeName(),
            'redelivery' => $message->isRedelivery(),
            'contentType' => $message->getContentType(),
            'contentEncoding' => $message->getContentEncoding(),
            'type' => $message->getType(),
            'timestamp' => $message->getTimeStamp(),
            'priority' => $message->getPriority(),
            'expiration' => $message->getExpiration(),
            'userId' => $message->getUserId(),
            'appId' => $message->getAppId(),
            'messageId' => $message->getMessageId(),
            'replyTo' => $message->getReplyTo(),
            'correlationId' => $message->getCorrelationId(),
            'headers' => $message->getHeaders(),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->body = $this->decodeStrategy->decode($this->body);
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getHeader($key)
    {
        return ArrayHelper::getValue($this->headers, $key);
    }
}