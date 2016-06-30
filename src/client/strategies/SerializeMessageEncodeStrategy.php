<?php

namespace yii\amqp\client\strategies;

/**
 * Class SerializeMessageEncodeStrategy
 *
 * @package yii\amqp\strategies\messages
 */
class SerializeMessageEncodeStrategy implements MessageEncodeDecodeStrategy
{
    /**
     * @param $message
     *
     * @return mixed
     */
    public function encode($message)
    {
        return serialize($message);
    }

    /**
     * @param string $message
     *
     * @return mixed
     */
    public function decode($message)
    {
        return unserialize($message);
    }
}