<?php

use yii\amqp\client\Client;
use yii\amqp\client\Configuration;
use yii\amqp\client\Envelope;

/**
 * Class ClientTest
 */
class ClientTest extends TestCase
{
    /**
     * @var Client
     */
    private $client;

    public function testConnection()
    {
        $this->assertNotNull($this->client);
        $this->assertTrue($this->client->isConnected());
    }

    public function testNewChannel()
    {
        $channel = $this->client->newChannel();

        $this->assertNotNull($channel);
        $this->assertTrue($channel->isConnected());
        $this->assertNotEmpty($channel->getChannelId());
    }

    public function testNewExchange()
    {
        $channel = $this->client->newChannel();
        $exchange = $this->client->newExchange($channel);

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
     * @return \yii\amqp\client\Exchange
     */
    private function declareExchangeAndCheckIt($name = null, $type = Client::EX_TYPE_DIRECT, $flags = Client::NOPARAM)
    {
        $channel = $this->client->newChannel();
        $exchange = $this->client->newExchange($channel);

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
    private function getNameForEntity($name, $type = Client::EX_TYPE_DIRECT)
    {
        return $name ?: sprintf('%s_%s', static::QUEUE_NAME, $type);
    }

    public function testExchangeDeclareTypeFanout()
    {
        $this->declareExchangeAndCheckIt(null, Client::EX_TYPE_FANOUT);
    }

    public function testExchangeDeclareTypeHeaders()
    {
        $this->declareExchangeAndCheckIt(null, Client::EX_TYPE_HEADERS);
    }

    public function testExchangeDeclareTypeTopic()
    {
        $this->declareExchangeAndCheckIt(null, Client::EX_TYPE_TOPIC);
    }

    public function testQueueDeclareTypeDirect()
    {
        $this->declareQueueAndCheckIt();
    }

    /**
     * @param string $name
     * @param int    $flags
     *
     * @return \yii\amqp\client\Queue
     */
    private function declareQueueAndCheckIt($name = null, $flags = Client::NOPARAM)
    {
        $channel = $this->client->newChannel();
        $queue = $this->client->newQueue($channel);

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

        $this->client = new Client([
            'readTimeout' => static::TIMEOUT,
            'writeTimeout' => static::TIMEOUT,
            'connectTimeout' => static::TIMEOUT,
        ]);

        $this->client->connect();
    }
}