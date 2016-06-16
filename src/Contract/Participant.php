<?php

namespace HelloFresh\Mailer\Contract;

interface Participant
{
    /**
     * @return string
     */
    public function getEmail();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $email
     * @return void
     */
    public function setEmail($email);

    /**
     * @param string $name
     * @return void
     */
    public function setName($name);
}
