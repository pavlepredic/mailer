<?php

namespace HelloFresh\Mailer\Implementation\Dummy\NoOp;

use HelloFresh\Mailer\Contract\EventProducerInterface;

class EventProducer implements EventProducerInterface
{
    /**
     * {@inheritdoc}
     */
    public function produce($message, $routingKey = null)
    {
        return true;
    }
}
