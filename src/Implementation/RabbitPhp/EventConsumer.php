<?php

namespace HelloFresh\Mailer\Implementation\RabbitPhp;

use HelloFresh\Mailer\Contract\EventConsumerInterface;
use HelloFresh\RabbitMQ\Connection;
use HelloFresh\RabbitMQ\Exchange\ExchangeInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class EventConsumer implements EventConsumerInterface
{
    const DEFAULT_PREFETCH_COUNT = 20;
    const DEFAULT_QUEUE_NAME = 'emails';

    /**
     * @var ExchangeInterface $exchange
     */
    private $exchange;

    /**
     * @var Connection $connection
     */
    private $connection;

    /**
     * @var string $queueName
     */
    private $queueName;

    /**
     * @var int $prefetchCount
     */
    private $prefetchCount;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * EventProducer constructor.
     * @param ExchangeInterface $exchange
     * @param Connection $connection
     * @param LoggerInterface $logger
     * @param string $queueName
     * @param int $prefetchCount
     */
    public function __construct(
        ExchangeInterface $exchange,
        Connection $connection,
        LoggerInterface $logger = null,
        $queueName = null,
        $prefetchCount = null
    ) {
        $this->exchange = $exchange;
        $this->connection = $connection;

        if (!$logger) {
            $logger = new NullLogger();
        }
        $this->logger = $logger;

        if ($prefetchCount === null) {
            $prefetchCount = self::DEFAULT_PREFETCH_COUNT;
        }
        $this->prefetchCount = $prefetchCount;

        if (!$queueName) {
            $queueName = self::DEFAULT_QUEUE_NAME;
        }
        $this->queueName = $queueName;
    }

    public function consume(callable $callback, $routingKey = null)
    {
        $exchange = $this->getExchange();
        /** @var AMQPChannel $channel */
        $channel = $this->getConnection()->channel();
        $channel->basic_qos(0, $this->prefetchCount, false);
        $channel->exchange_declare($exchange->getName(), $exchange->getType(), $exchange->getPassive(), $exchange->getDurable(), $exchange->getAutoDelete());
        $channel->queue_declare($this->queueName, false, true, false, false);
        $channel->queue_bind($this->queueName, $exchange->getName(), $routingKey);

        $this->getLogger()->info('Waiting for messages...');

        $channel->basic_consume(
            $this->queueName,
            '',
            false,
            false,
            false,
            false,
            function(AMQPMessage $message) use ($callback) {
                $response = call_user_func($callback, $message->getBody());
                if ($response) {
                    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
                }
            }
        );
    }

    /**
     * @return ExchangeInterface
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * @param ExchangeInterface $exchange
     * @return EventProducer
     */
    public function setExchange(ExchangeInterface $exchange)
    {
        $this->exchange = $exchange;
        return $this;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param Connection $connection
     * @return EventConsumer
     */
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return EventConsumer
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }
}
