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
     * @param $message
     *
     * @return mixed
     */
    public function encode($message);
    
    /**
     * @param string $message
     *
     * @return mixed
     */
    public function decode($message);
}