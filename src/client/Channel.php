<?php

namespace yii\amqp\client;

use yii\amqp\helpers\ClientHelper;
use yii\base\BaseObject;

/**
 * Class Channel
 *
 * @package yii\amqp
 */
class Channel extends BaseObject
{
    /**
     * @var Client
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
            ClientHelper::throwRightException($e);
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
     */
    public function setPrefetchSize($size)
    {
        try {
            return $this->rawChannel->setPrefetchSize($size);
        } catch (\AMQPConnectionException $e) {
            ClientHelper::throwRightException($e);
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
     */
    public function setPrefetchCount($count)
    {
        try {
            return $this->rawChannel->setPrefetchCount($count);
        } catch (\AMQPConnectionException $e) {
            ClientHelper::throwRightException($e);
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
     */
    public function qos($size, $count)
    {
        try {
            return $this->rawChannel->qos($size, $count);
        } catch (\AMQPConnectionException $e) {
            ClientHelper::throwRightException($e);
        }
    }

    /**
     * @return bool
     */
    public function startTransaction()
    {
        try {
            return $this->rawChannel->startTransaction();
        } catch (\AMQPConnectionException $e) {
            ClientHelper::throwRightException($e);
        }
    }

    /**
     * @return bool
     */
    public function commitTransaction()
    {
        try {
            return $this->rawChannel->commitTransaction();
        } catch (\Exception $e) {
            ClientHelper::throwRightException($e);
        }
    }

    /**
     * @return bool
     */
    public function rollbackTransaction()
    {
        try {
            return $this->rawChannel->rollbackTransaction();
        } catch (\Exception $e) {
            ClientHelper::throwRightException($e);
        }
    }

    /**
     * @return Client
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
