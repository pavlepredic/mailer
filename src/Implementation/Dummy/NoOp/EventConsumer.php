<?php

namespace HelloFresh\Mailer\Implementation\Dummy\NoOp;

use HelloFresh\Mailer\Contract\EventConsumerInterface;

class EventConsumer implements EventConsumerInterface
{
    /**
     * {@inheritdoc}
     */
    public function consume(callable $callback, $routingKey = null)
    {
    }
}
