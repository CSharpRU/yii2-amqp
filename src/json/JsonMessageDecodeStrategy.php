<?php

namespace yii\amqp\json;

use yii\amqp\MessageDecodeStrategy;
use yii\helpers\Json;

/**
 * Class JsonMessageDecodeStrategy
 *
 * @package yii\amqp
 */
class JsonMessageDecodeStrategy implements MessageDecodeStrategy
{
    /**
     * @param string $message
     *
     * @return mixed
     */
    public function decode($message)
    {
        return Json::decode($message);
    }
}