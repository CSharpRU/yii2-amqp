<?php

namespace yii\amqp\client\strategies;

/**
 * Interface MessageEncodeStrategy
 *
 * @package yii\amqp
 */
interface MessageEncodeDecodeStrategy
{
    /**
     * @param mixed $message
     *
     * @return string
     */
    public function encode($message);
    
    /**
     * @param string $message
     *
     * @return mixed
     */
    public function decode($message);
}