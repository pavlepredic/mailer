<?php

namespace HelloFresh\Mailer\Contract;

interface ArrayableInterface
{
    /**
     * @return array
     */
    public function toArray();

    /**
     * @param array $array
     * @return ArrayableInterface
     */
    public static function fromArray(array $array);
}
