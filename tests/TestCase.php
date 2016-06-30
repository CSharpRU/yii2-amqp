<?php

/**
 * Class TestCase
 */
abstract class TestCase extends PHPUnit_Framework_TestCase
{
    const TIMEOUT = 5;
    const QUEUE_NAME = 'yii2.amqp.test_queue';
}