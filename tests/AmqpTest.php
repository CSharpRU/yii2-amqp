<?php

use yii\amqp\Amqp;
use yii\amqp\client\Client;
use yii\amqp\client\Envelope;
use yii\helpers\ArrayHelper;

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

    public function testPublishAndConsumeDifferentType()
    {
        $amqp = $this->getAmqp(Client::MESSAGE_TYPE_JSON);
        $message = new TestMessage();

        $message->data = ['testArray', 'timestamp' => time()];

        $this->assertTrue($amqp->publish($message));

        $amqp->consume(function (Envelope $envelope) use ($message, $amqp) {
            $this->assertEquals($envelope->body, ArrayHelper::toArray($message));

            $amqp->ack($envelope->deliveryTag);

            return false;
        });
    }

    /**
     * @param string $messageType
     *
     * @return Amqp
     */
    private function getAmqp($messageType = Client::MESSAGE_TYPE_SERIALIZE)
    {
        static $client;

        if (!$client) {
            $client = new Client([
                'readTimeout' => static::TIMEOUT,
                'writeTimeout' => static::TIMEOUT,
                'connectTimeout' => static::TIMEOUT,
            ]);
        }

        return new Amqp($client, [
            'name' => static::QUEUE_NAME,
            'messageType' => $messageType,
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->amqp = $this->getAmqp();
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