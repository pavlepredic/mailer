<?php

namespace HelloFresh\Mailer\Implementation\Dummy\Queue;

class QueueFactory
{
    /**
     * @var \SplQueue[] $queues
     */
    private static $queues;

    /**
     * Returns a queue by the given name
     * or creates one if it does not exist
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
