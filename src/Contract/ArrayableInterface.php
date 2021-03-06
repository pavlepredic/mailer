<?php

namespace HelloFresh\Mailer\Contract;

use HelloFresh\Mailer\Exception\SerializationException;

interface ArrayableInterface
{
    /**
     * @return array
     */
    public function toArray();

    /**
     * @param array $array
     * @return ArrayableInterface
     * @throws SerializationException
     */
    public static function fromArray(array $array);

    /**
     * Returns an array of types the input array should contain
     * @return array
     */
    public static function getArrayDefinition();
}
