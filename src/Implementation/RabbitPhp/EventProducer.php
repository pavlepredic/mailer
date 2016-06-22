<?php

namespace HelloFresh\Mailer\Implementation\RabbitPhp;

use HelloFresh\Mailer\Contract\EventProducerInterface;
use HelloFresh\RabbitMQ\Exchange\ExchangeInterface;

class EventProducer implements EventProducerInterface
{
    /**
     * @var ExchangeInterface $exchange
     */
    private $exchange;

    /**
     * EventProducer constructor.
     * @param ExchangeInterface $exchange
     */
    public function __construct(ExchangeInterface $exchange)
    {
        $this->exchange = $exchange;
    }

    /**
     * {@inheritdoc}
     */
    public function produce($message, $routingKey = null)
    {
        return $this->exchange->publish($message, $routingKey);
    }
}
