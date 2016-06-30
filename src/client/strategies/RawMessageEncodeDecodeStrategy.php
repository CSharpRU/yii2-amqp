<?php

namespace yii\amqp\client\strategies;

/**
 * Class RawMessageEncodeDecodeStrategy
 *
 * @package yii\amqp\raw
 */
class RawMessageEncodeDecodeStrategy implements MessageEncodeDecodeStrategy
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