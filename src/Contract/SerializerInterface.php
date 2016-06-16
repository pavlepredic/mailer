<?php

namespace HelloFresh\Mailer\Contract;
use HelloFresh\Mailer\Exception\SerializationException;

/**
 * Provides methods for serializing and unserializing MessageInterface instances
 */
interface SerializerInterface
{
    /**
     * @param MessageInterface $message
     * @return string
     */
    public function serialize(MessageInterface $message);

    /**
     * @param string $string
     * @return MessageInterface
     * @throws SerializationException
     */
    public function unserialize($string);
}
