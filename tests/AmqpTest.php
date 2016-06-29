<?php

use yii\amqp\Amqp;
use yii\amqp\Configuration;
use yii\amqp\Envelope;

/**
 * Class AmqpTest
 */
class AmqpTest extends TestCase
{
    const QUEUE_NAME = 'yii2.amqp.test_queue';
    const TIMEOUT = 5;

    /**
     * @var Amqp
     */
    private $amqp;

    public function testConnection()
    {
        $this->assertNotNull($this->amqp);
        $this->assertTrue($this->amqp->isConnected());
    }

    public function testNewChannel()
    {
        $channel = $this->amqp->newChannel();

        $this->assertNotNull($channel);
        $this->assertTrue($channel->isConnected());
        $this->assertNotEmpty($channel->getChannelId());
    }

    public function testNewExchange()
    {
        $channel = $this->amqp->newChannel();
        $exchange = $this->amqp->newExchange($channel);

        $this->assertNotNull($exchange);
    }

    public function testExchangeDeclareTypeDirect()
    {
        $this->declareExchangeAndCheckIt();
    }

    /**
     * @param string $name
     * @param string $type
     * @param int    $flags
     *
     * @return \yii\amqp\Exchange
     */
    private function declareExchangeAndCheckIt($name = null, $type = Amqp::EX_TYPE_DIRECT, $flags = Amqp::DURABLE)
    {
        $channel = $this->amqp->newChannel();
        $exchange = $this->amqp->newExchange($channel);

        $exchange->setName($this->getNameForEntity($name, $type));
        $exchange->setType($type);
        $exchange->setFlags($flags);

        $this->assertNotNull($exchange);
        $this->assertTrue($exchange->declareExchange());
        $this->assertEquals($type, $exchange->getType());
        $this->assertEquals($flags, $exchange->getFlags());

        return $exchange;
    }

    /**
     * @param bool $name
     * @param      $type
     *
     * @return string
     */
    private function getNameForEntity($name, $type = Amqp::EX_TYPE_DIRECT)
    {
        return $name ?: sprintf('%s_%s', self::QUEUE_NAME, $type);
    }

    public function testExchangeDeclareTypeFanout()
    {
        $this->declareExchangeAndCheckIt(Amqp::EX_TYPE_FANOUT);
    }

    public function testExchangeDeclareTypeHeaders()
    {
        $this->declareExchangeAndCheckIt(Amqp::EX_TYPE_HEADERS);
    }

    public function testExchangeDeclareTypeTopic()
    {
        $this->declareExchangeAndCheckIt(Amqp::EX_TYPE_TOPIC);
    }

    public function testQueueDeclareTypeDirect()
    {
        $this->declareQueueAndCheckIt();
    }

    /**
     * @param string $name
     * @param int    $flags
     *
     * @return \yii\amqp\Queue
     */
    private function declareQueueAndCheckIt($name = null, $flags = Amqp::DURABLE)
    {
        $channel = $this->amqp->newChannel();
        $queue = $this->amqp->newQueue($channel);

        $queue->setName($this->getNameForEntity($name));
        $queue->setFlags($flags);

        $this->assertNotNull($queue);
        $this->assertGreaterThanOrEqual(0, $queue->declareQueue());
        $this->assertEquals($flags, $queue->getFlags());

        return $queue;
    }

    public function testWriteToExchangeAndReadFromQueue()
    {
        $exchange = $this->declareExchangeAndCheckIt();
        $queue = $this->declareQueueAndCheckIt();

        $queue->bind($exchange);

        $data = ['testArray' => 'Cool!', 'timestamp' => time()];

        $exchange->publish($data);

        $queue->consume(function (Envelope $envelope) use ($queue, $data) {
            $this->assertNotNull($envelope);
            $this->assertEquals($data, $envelope->body);

            $queue->ack($envelope->deliveryTag);

            return false;
        });
    }

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->amqp = new Amqp([
            'configuration' => new Configuration([
                'readTimeout' => self::TIMEOUT,
                'writeTimeout' => self::TIMEOUT,
                'connectTimeout' => self::TIMEOUT,
            ]),
        ]);
    }
}