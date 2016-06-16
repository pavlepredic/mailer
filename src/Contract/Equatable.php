<?php

namespace HelloFresh\Mailer\Contract;

interface Equatable
{
    /**
     * @param Equatable $object
     * @return boolean
     */
    public function equals(Equatable $object);
}
