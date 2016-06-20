<?php

namespace HelloFresh\Mailer\Contract;

interface EventConsumerInterface
{
    /**
     * @param callable $callback
     * @param string $routingKey
     * @return bool
     */
    public function consume(callable $callback, $routingKey = null);
}
