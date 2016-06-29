<?php

namespace yii\amqp\raw;

use yii\amqp\MessageDecodeStrategy;

/**
 * Class RawMessageDecodeStrategy
 *
 * @package yii\amqp
 */
class RawMessageDecodeStrategy implements MessageDecodeStrategy
{
    /**
     * @param string $message
     *
     * @return mixed
     */
    public function decode($message)
    {
        return $message;
    }
}