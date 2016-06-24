<?php

namespace HelloFresh\Mailer\Contract;

interface EventConsumerInterface
{
    /**
     * @param callable $callback
     * @param string $routingKey
     * @return void
     */
    public function consume(callable $callback, $routingKey = null);
}
