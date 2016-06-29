<?php

namespace yii\amqp;

use yii\amqp\helpers\ExceptionHelper;
use yii\base\Object;

/**
 * Class Queue
 *
 * @package yii\amqp
 */
class Queue extends Object
{
    /**
     * @var Channel
     */
    public $channel;

    /**
     * @var \AMQPQueue
     */
    protected $rawQueue;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        try {
            $this->rawQueue = new \AMQPQueue($this->channel->getRawChannel());
        } catch (\Exception $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->rawQueue->getName();
    }

    /**
     * @param $queueName
     *
     * @return bool
     */
    public function setName($queueName)
    {
        return $this->rawQueue->setName($queueName);
    }

    /**
     * @return int
     */
    public function getFlags()
    {
        return $this->rawQueue->getFlags();
    }

    /**
     * @param $flags
     *
     * @return bool
     */
    public function setFlags($flags)
    {
        return $this->rawQueue->setFlags($flags);
    }

    /**
     * @param $key
     *
     * @return bool|int|string
     */
    public function getArgument($key)
    {
        return $this->rawQueue->getArgument($key);
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->rawQueue->getArguments();
    }

    /**
     * @param $key
     * @param $value
     *
     * @return bool
     */
    public function setArgument($key, $value)
    {
        return $this->rawQueue->setArgument($key, $value);
    }

    /**
     * @param array $arguments
     *
     * @return bool
     */
    public function setArguments(array $arguments)
    {
        return $this->rawQueue->setArguments($arguments);
    }

    /**
     * @return int
     */
    public function declareQueue()
    {
        try {
            return $this->rawQueue->declareQueue();
        } catch (\Exception $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @param Exchange|string $exchange
     * @param string          $routingKey
     * @param array           $arguments
     *
     * @return bool
     */
    public function bind($exchange, $routingKey = null, array $arguments = [])
    {
        $exchange = $this->getExchangeName($exchange);

        try {
            return $this->rawQueue->bind($exchange, $routingKey, $arguments);
        } catch (\Exception $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @param $exchange
     *
     * @return string
     */
    private function getExchangeName($exchange)
    {
        return !($exchange instanceof Exchange) ? $exchange : $exchange->getName();
    }

    /**
     * @param int $flags
     *
     * @return Envelope|bool
     */
    public function get($flags = Amqp::NOPARAM)
    {
        try {
            $message = $this->rawQueue->get($flags);

            if ($message instanceof \AMQPEnvelope) {
                $message = Envelope::createFromRaw($message);
            }

            return $message;
        } catch (\Exception $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @param callable|null $callback
     * @param int           $flags
     * @param string        $consumerTag
     */
    public function consume(
        callable $callback = null,
        $flags = Amqp::NOPARAM,
        $consumerTag = null
    ) {
        \Yii::$container->get(Envelope::class);
        try {
            $this->rawQueue->consume(function (\AMQPEnvelope $envelope) use ($callback) {
                return $callback(Envelope::createFromRaw($envelope), $this);
            }, $flags, $consumerTag);
        } catch (\Exception $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @param string $deliveryTag
     * @param int    $flags
     *
     * @return bool
     */
    public function ack($deliveryTag, $flags = Amqp::NOPARAM)
    {
        try {
            return $this->rawQueue->ack($deliveryTag, $flags);
        } catch (\Exception $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @param string $deliveryTag
     * @param int    $flags
     *
     * @return bool
     */
    public function nack($deliveryTag, $flags = Amqp::NOPARAM)
    {
        try {
            return $this->rawQueue->nack($deliveryTag, $flags);
        } catch (\Exception $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @param string $deliveryTag
     * @param int    $flags
     *
     * @return bool
     */
    public function reject($deliveryTag, $flags = Amqp::NOPARAM)
    {
        try {
            return $this->rawQueue->reject($deliveryTag, $flags);
        } catch (\Exception $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @return bool
     */
    public function purge()
    {
        try {
            return $this->rawQueue->purge();
        } catch (\Exception $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @param string $consumerTag
     *
     * @return bool
     */
    public function cancel($consumerTag = '')
    {
        try {
            return $this->rawQueue->cancel($consumerTag);
        } catch (\Exception $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @param Exchange|string $exchange
     * @param string          $routingKey
     * @param array           $arguments
     *
     * @return bool
     */
    public function unbind($exchange, $routingKey = null, array $arguments = [])
    {
        $exchange = $this->getExchangeName($exchange);

        try {
            return $this->rawQueue->unbind($exchange, $routingKey, $arguments);
        } catch (\Exception $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @param int $flags
     *
     * @return int
     */
    public function delete($flags = Amqp::NOPARAM)
    {
        try {
            return $this->rawQueue->delete($flags);
        } catch (\Exception $e) {
            ExceptionHelper::throwRightException($e);
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
     * @return Amqp
     */
    public function getConnection()
    {
        return $this->channel->amqp;
    }
}