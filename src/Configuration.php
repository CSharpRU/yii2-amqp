<?php

namespace yii\amqp;

use yii\base\Object;

/**
 * Class ConnectionConfiguration
 *
 * @package yii\amqp
 */
class Configuration extends Object
{
    /**
     * @var string
     */
    public $host = 'localhost';

    /**
     * @var string
     */
    public $virtualHost = '';

    /**
     * @var int
     */
    public $port = 5672;

    /**
     * @var string
     */
    public $login = 'guest';

    /**
     * @var string
     */
    public $password = 'guest';

    /**
     * @var int
     */
    public $readTimeout = 0;

    /**
     * @var int
     */
    public $writeTimeout = 0;

    /**
     * @var int
     */
    public $connectTimeout = 0;

    /**
     * @return array
     */
    public function toAmqpCredentialsArray()
    {
        return [
            'host' => $this->host,
            'port' => $this->port,
            'vhost' => $this->virtualHost,
            'login' => $this->login,
            'password' => $this->password,
            'read_timeout' => $this->readTimeout,
            'write_timeout' => $this->writeTimeout,
            'connect_timeout' => $this->connectTimeout,
        ];
    }
}