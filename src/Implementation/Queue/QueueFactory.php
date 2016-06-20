<?php

namespace HelloFresh\Mailer\Implementation\Queue;

class QueueFactory
{
    /**
     * @var \SplQueue[] $queues
     */
    private static $queues;

    /**
     * @param string $name
     * @return \SplQueue
     */
    public static function make($name)
    {
        if (!isset(self::$queues[$name])) {
            self::$queues[$name] = new \SplQueue();
        }

        return self::$queues[$name];
    }

}
