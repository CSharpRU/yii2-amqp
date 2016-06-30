<?php

namespace yii\amqp\helpers;

use yii\amqp\exceptions\client\ChannelException;
use yii\amqp\exceptions\client\ConnectionException;
use yii\amqp\exceptions\client\ExchangeException;
use yii\amqp\exceptions\client\QueueException;
use yii\helpers\ArrayHelper;

/**
 * Class ClientHelper
 *
 * @package yii\amqp\client\helpers
 */
class ClientHelper
{
    /**
     * @param \Exception $e
     *
     * @throws \RuntimeException
     * @throws ConnectionException
     * @throws ChannelException
     * @throws ExchangeException
     */
    public static function throwRightException(\Exception $e)
    {
        $map = [
            \AMQPConnectionException::class => ConnectionException::class,
            \AMQPChannelException::class => ChannelException::class,
            \AMQPExchangeException::class => ExchangeException::class,
            \AMQPQueueException::class => QueueException::class,
        ];

        $exceptionName = get_class($e);
        $exceptionClassName = ArrayHelper::getValue($map, $exceptionName);

        if (!$exceptionClassName) {
            throw new \RuntimeException(\Yii::t('yii', 'Unknown exception class in map for {exceptionName}!',
                ['exceptionName' => $exceptionName]), $e->getCode(), $e);
        }

        throw new $exceptionClassName($e->getMessage(), $e->getCode(), $e);
    }
}