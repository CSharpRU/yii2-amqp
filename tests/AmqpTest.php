<?php

use yii\amqp\Amqp;
use yii\amqp\client\Client;
use yii\amqp\client\Envelope;

/**
 * Class AmqpTest
 */
class AmqpTest extends TestCase
{
    /**
     * @var Amqp
     */
    private $amqp;

    public function testPublish()
    {
        $message = new TestMessage();

        $message->data = ['testArray'];

        $this->assertTrue($this->amqp->publish($message));
        $this->assertTrue($this->amqp->purge());
    }

    public function testPublishAndConsume()
    {
        $message = new TestMessage();

        $message->data = ['testArray', 'timestamp' => time()];

        $this->assertTrue($this->amqp->publish($message));

        $this->amqp->consume(function (Envelope $envelope) use ($message) {
            $this->assertEquals($envelope->body, $message);

            $this->amqp->ack($envelope->deliveryTag);

            return false;
        });
    }

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->amqp = new Amqp(new Client([
            'readTimeout' => static::TIMEOUT,
            'writeTimeout' => static::TIMEOUT,
            'connectTimeout' => static::TIMEOUT,
        ]), ['name' => static::QUEUE_NAME]);
    }
}

class TestMessage
{
    /**
     * @var array
     */
    public $data = [];

    /**
     * @var string
     */
    public $type = 'test';
}