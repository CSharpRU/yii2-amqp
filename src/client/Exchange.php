<?php

namespace yii\amqp\client;

use yii\amqp\client\strategies\MessageEncodeDecodeStrategy;
use yii\amqp\helpers\AmqpHelper;
use yii\amqp\helpers\ClientHelper;
use yii\base\BaseObject;

/**
 * Class Exchange
 *
 * @package yii\amqp
 */
class Exchange extends BaseObject
{
    /**
     * @var Channel
     */
    public $channel;

    /**
     * @var \AMQPExchange
     */
    protected $rawExchange;

    /**
     * @var MessageEncodeDecodeStrategy
     */
    protected $encodeStrategy;

    /**
     * @inheritDoc
     */
    public function __construct(MessageEncodeDecodeStrategy $encodeStrategy, $config = [])
    {
        parent::__construct($config);

        $this->encodeStrategy = $encodeStrategy;
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        try {
            $this->rawExchange = new \AMQPExchange($this->channel->getRawChannel());
        } catch (\Exception $e) {
            ClientHelper::throwRightException($e);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->rawExchange->getName();
    }

    /**
     * @param $exchangeName
     *
     * @return bool
     */
    public function setName($exchangeName)
    {
        return $this->rawExchange->setName($exchangeName);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->rawExchange->getType();
    }

    /**
     * @param $exchangeType
     *
     * @return bool
     */
    public function setType($exchangeType)
    {
        return $this->rawExchange->setType($exchangeType);
    }

    /**
     * @return int
     */
    public function getFlags()
    {
        return $this->rawExchange->getFlags();
    }

    /**
     * @param $flags
     *
     * @return bool
     */
    public function setFlags($flags)
    {
        return $this->rawExchange->setFlags($flags);
    }

    /**
     * @param $key
     *
     * @return bool|int|string
     */
    public function getArgument($key)
    {
        return $this->rawExchange->getArgument($key);
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->rawExchange->getArguments();
    }

    /**
     * @param $key
     * @param $value
     *
     * @return bool
     */
    public function setArgument($key, $value)
    {
        return $this->rawExchange->setArgument($key, $value);
    }

    /**
     * @param array $arguments
     *
     * @return bool
     */
    public function setArguments(array $arguments)
    {
        return $this->rawExchange->setArguments($arguments);
    }

    /**
     * @return bool
     */
    public function declareExchange()
    {
        try {
            return $this->rawExchange->declareExchange();
        } catch (\Exception $e) {
            ClientHelper::throwRightException($e);
        }
    }

    /**
     * @param string $exchangeName
     * @param int    $flags
     *
     * @return bool
     */
    public function delete($exchangeName = null, $flags = Client::NOPARAM)
    {
        try {
            return $this->rawExchange->delete($exchangeName, $flags);
        } catch (\Exception $e) {
            ClientHelper::throwRightException($e);
        }
    }

    /**
     * @param Exchange|string $exchange
     * @param string          $routingKey
     * @param array           $arguments
     *
     * @return bool
     */
    public function bind($exchange, $routingKey = '', array $arguments = [])
    {
        $exchange = AmqpHelper::getExchangeName($exchange);

        try {
            return $this->rawExchange->bind($exchange, $routingKey, $arguments);
        } catch (\Exception $e) {
            ClientHelper::throwRightException($e);
        }
    }

    /**
     * @param Exchange|string $exchange
     * @param string          $routingKey
     * @param array           $arguments
     *
     * @return bool
     */
    public function unbind($exchange, $routingKey = '', array $arguments = [])
    {
        $exchange = AmqpHelper::getExchangeName($exchange);

        try {
            return $this->rawExchange->unbind($exchange, $routingKey, $arguments);
        } catch (\Exception $e) {
            ClientHelper::throwRightException($e);
        }
    }

    /**
     * @param mixed  $message
     * @param string $routingKey
     * @param int    $flags
     * @param array  $attributes
     *
     * @return bool
     */
    public function publish($message, $routingKey = null, $flags = Client::NOPARAM, array $attributes = [])
    {
        try {
            return $this->rawExchange->publish($this->encodeStrategy->encode($message), $routingKey, $flags,
                $attributes);
        } catch (\Exception $e) {
            ClientHelper::throwRightException($e);
        }
    }

    /**
     * @return Channel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return Client
     */
    public function getConnection()
    {
        return $this->channel->amqp;
    }
}
