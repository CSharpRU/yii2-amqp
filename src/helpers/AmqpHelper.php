<?php

namespace yii\amqp\helpers;

use yii\amqp\Exchange;

/**
 * Class AmqpHelper
 *
 * @package yii\amqp\helpers
 */
class AmqpHelper
{
    /**
     * @param Exchange|string $exchange
     *
     * @return string
     */
    public static function getExchangeName($exchange)
    {
        return !($exchange instanceof Exchange) ? $exchange : $exchange->getName();
    }
}