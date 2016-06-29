<?php


namespace yii\amqp;

/**
 * Interface MessageDecodeStrategy
 *
 * @package yii\amqp
 */
interface MessageDecodeStrategy
{
    /**
     * @param string $message
     *
     * @return mixed
     */
    public function decode($message);
}