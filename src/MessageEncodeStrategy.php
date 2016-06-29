<?php

namespace yii\amqp;

/**
 * Interface MessageEncodeStrategy
 *
 * @package yii\amqp
 */
interface MessageEncodeStrategy
{
    /**
     * @param $message
     *
     * @return mixed
     */
    public function encode($message);
}