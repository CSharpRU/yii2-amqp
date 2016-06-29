<?php

namespace yii\amqp;

use yii\amqp\helpers\ExceptionHelper;
use yii\base\Object;

/**
 * Class Channel
 *
 * @package yii\amqp
 */
class Channel extends Object
{
    /**
     * @var Amqp
     */
    public $amqp;

    /**
     * @var \AMQPChannel
     */
    protected $rawChannel;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        try {
            $this->rawChannel = new \AMQPChannel($this->amqp->getRawConnection());
        } catch (\AMQPConnectionException $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        return $this->rawChannel->isConnected();
    }

    /**
     * @return int
     */
    public function getChannelId()
    {
        return $this->rawChannel->getChannelId();
    }

    /**
     * @param $size
     *
     * @return bool
     * @throws exceptions\ConnectionException
     */
    public function setPrefetchSize($size)
    {
        try {
            return $this->rawChannel->setPrefetchSize($size);
        } catch (\AMQPConnectionException $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @return int
     */
    public function getPrefetchSize()
    {
        return $this->rawChannel->getPrefetchSize();
    }

    /**
     * @param $count
     *
     * @return bool
     * @throws exceptions\ConnectionException
     */
    public function setPrefetchCount($count)
    {
        try {
            return $this->rawChannel->setPrefetchCount($count);
        } catch (\AMQPConnectionException $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @return int
     */
    public function getPrefetchCount()
    {
        return $this->rawChannel->getPrefetchCount();
    }

    /**
     * @param $size
     * @param $count
     *
     * @return bool
     * @throws exceptions\ConnectionException
     */
    public function qos($size, $count)
    {
        try {
            return $this->rawChannel->qos($size, $count);
        } catch (\AMQPConnectionException $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @return bool
     * @throws exceptions\ConnectionException
     */
    public function startTransaction()
    {
        try {
            return $this->rawChannel->startTransaction();
        } catch (\AMQPConnectionException $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @return bool
     * @throws exceptions\ChannelException
     * @throws exceptions\ConnectionException
     */
    public function commitTransaction()
    {
        try {
            return $this->rawChannel->commitTransaction();
        } catch (\Exception $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @return bool
     * @throws exceptions\ChannelException
     * @throws exceptions\ConnectionException
     */
    public function rollbackTransaction()
    {
        try {
            return $this->rawChannel->rollbackTransaction();
        } catch (\Exception $e) {
            ExceptionHelper::throwRightException($e);
        }
    }

    /**
     * @return Amqp
     */
    public function getConnection()
    {
        return $this->amqp;
    }

    /**
     * @param bool $requeue
     *
     * @return $this
     */
    public function basicRecover($requeue = true)
    {
        $this->rawChannel->basicRecover($requeue);

        return $this;
    }

    /**
     * @return \AMQPChannel
     */
    public function getRawChannel()
    {
        return $this->rawChannel;
    }
}