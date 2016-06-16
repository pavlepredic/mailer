<?php

namespace HelloFresh\Mailer\Contract;

interface Participant extends Equatable
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
     * @return Participant
     */
    public function setEmail($email);

    /**
     * @param string $name
     * @return Participant
     */
    public function setName($name);
}
