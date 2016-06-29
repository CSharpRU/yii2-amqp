<?php

namespace yii\amqp\json;

use yii\amqp\MessageEncodeStrategy;
use yii\helpers\Json;

/**
 * Class JsonMessageEncodeStrategy
 *
 * @package yii\amqp
 */
class JsonMessageEncodeStrategy implements MessageEncodeStrategy
{
    /**
     * @param $message
     *
     * @return mixed
     */
    public function encode($message)
    {
        return Json::encode($message);
    }
}