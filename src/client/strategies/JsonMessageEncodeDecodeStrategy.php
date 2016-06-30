<?php

namespace yii\amqp\client\strategies;

use yii\helpers\Json;

/**
 * Class JsonMessageEncodeDecodeStrategy
 *
 * @package yii\amqp\json
 */
class JsonMessageEncodeDecodeStrategy implements MessageEncodeDecodeStrategy
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