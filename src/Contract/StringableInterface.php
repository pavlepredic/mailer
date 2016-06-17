<?php

namespace HelloFresh\Mailer\Contract;

use HelloFresh\Mailer\Exception\InvalidArgumentException;

interface StringableInterface
{
    /**
     * @return string
     */
    public function toString();

    /**
     * @param $string
     * @return StringableInterface
     * @throws InvalidArgumentException
     */
    public static function fromString($string);
}
