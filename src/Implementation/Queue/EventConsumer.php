<?php

namespace HelloFresh\Mailer\Implementation\Queue;

use HelloFresh\Mailer\Contract\EventConsumerInterface;

/**
 * Implementation of EventConsumerInterface that
 * consumes events from an SplQueue.
 * Handy for testing.
 */
class EventConsumer implements EventConsumerInterface
{
    /**
     * {@inheritdoc}
     */
    public function consume(callable $callback, $routingKey = null)
    {
        $queue = QueueFactory::make($routingKey);
        while (!$queue->isEmpty()) {
            $message = $queue->dequeue();
            call_user_func($callback, $message);
        }
    }
}
