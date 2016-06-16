<?php

namespace HelloFresh\Mailer\Contract;

use HelloFresh\Mailer\Exception\InvalidArgumentException;

interface PriorityInterface extends EquatableInterface
{
    /**
     * @return string
     */
    public function toString();

    /**
     * @param $string
     * @return PriorityInterface
     * @throws InvalidArgumentException
     */
    public static function fromString($string);
}
