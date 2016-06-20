<?php

namespace HelloFresh\Mailer\Contract;

interface EventProducerInterface
{
    /**
     * @param string $message
     * @param string $routingKey
     * @return bool
     */
    public function produce($message, $routingKey = null);
}
