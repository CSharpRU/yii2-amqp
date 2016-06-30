<?php

namespace yii\amqp\client;

use yii\amqp\client\strategies\MessageEncodeDecodeStrategy;
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
     * @var MessageEncodeDecodeStrategy
     */
    public $decodeStrategy;

    /**
     * @param \AMQPEnvelope                    $message
     * @param MessageEncodeDecodeStrategy|null $decodeStrategy
     *
     * @return static
     * @throws \yii\base\InvalidConfigException
     */
    public static function createFromRaw(\AMQPEnvelope $message, $decodeStrategy = null)
    {
        return \Yii::createObject([
            'class' => static::class,
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
            'decodeStrategy' => $decodeStrategy,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->body = $this->decodeStrategy ? $this->decodeStrategy->decode($this->body) : $this->body;
    }

    /**
     * @param $key
     *
     * @return mixed
     * @throws \yii\base\InvalidParamException
     */
    public function getHeader($key)
    {
        return ArrayHelper::getValue($this->headers, $key);
    }
}