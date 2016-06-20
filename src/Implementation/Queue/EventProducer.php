<?php

namespace HelloFresh\Mailer\Implementation\Queue;

use HelloFresh\Mailer\Contract\EventProducerInterface;

/**
 * Implementation of EventProducerInterface that
 * publishes events to an SplQueue.
 * Handy for testing.
 */
class EventProducer implements EventProducerInterface
{
    /**
     * {@inheritdoc}
     */
    public function produce($message, $routingKey = null)
    {
        $queue = QueueFactory::make($routingKey);
        $queue->enqueue($message);
        return true;
    }

}
