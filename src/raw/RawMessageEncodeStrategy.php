<?php

namespace yii\amqp\raw;

use yii\amqp\MessageEncodeStrategy;

/**
 * Class RawMessageEncodeStrategy
 *
 * @package yii\amqp
 */
class RawMessageEncodeStrategy implements MessageEncodeStrategy
{
    /**
     * @param $message
     *
     * @return mixed
     */
    public function encode($message)
    {
        return $message;
    }
}