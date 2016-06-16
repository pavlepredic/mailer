<?php

namespace HelloFresh\Mailer\Contract;

interface EquatableInterface
{
    /**
     * @param EquatableInterface $object
     * @return boolean
     */
    public function equals(EquatableInterface $object);
}
