<?php

namespace HelloFresh\Mailer\Contract;

interface ParticipantInterface extends EquatableInterface
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
     * @return ParticipantInterface
     */
    public function setEmail($email);

    /**
     * @param string $name
     * @return ParticipantInterface
     */
    public function setName($name);
}
