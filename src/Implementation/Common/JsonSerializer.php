<?php

namespace HelloFresh\Mailer\Implementation\Common;

use HelloFresh\Mailer\Contract\MessageInterface;
use HelloFresh\Mailer\Contract\SerializerInterface;
use HelloFresh\Mailer\Exception\SerializationException;

class JsonSerializer implements SerializerInterface
{
    /**
     * @var int $options
     * @see json_encode
     */
    private $options;

    /**
     * @var int $depth
     * @see json_encode
     */
    private $depth;

    /**
     * @param int $options
     * @param int $depth
     */
    public function __construct($options = 0, $depth = 512)
    {
        $this->options = $options;
        $this->depth = $depth;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(MessageInterface $message)
    {
        $array = $message->toArray();
        $array[] = get_class($message);
        return json_encode($array, $this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($string)
    {
        $array = json_decode($string, true, $this->depth, $this->options);
        $class = array_pop($array);
        if (!class_exists($class)) {
            throw new SerializationException("Class $class does not exist");
        }
        if (!(new $class) instanceof MessageInterface) {
            throw new SerializationException("Class $class does not implement MessageInterface");
        }

        return $class::fromArray($array);
    }
}

