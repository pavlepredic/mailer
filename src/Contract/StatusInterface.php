<?php

namespace HelloFresh\Mailer\Contract;

interface StatusInterface
{
    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     * @return StatusInterface
     */
    public function setMessage($message);
}
