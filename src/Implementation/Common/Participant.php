<?php

namespace HelloFresh\Mailer\Implementation\Common;

use HelloFresh\Mailer\Contract\EquatableInterface;
use HelloFresh\Mailer\Contract\ParticipantInterface;

class Participant implements ParticipantInterface
{
    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $email
     */
    private $email;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function equals(EquatableInterface $object)
    {
        return
            $object instanceof self
            and
            $this->getName() === $object->getName()
            and
            $this->getEmail() === $object->getEmail()
        ;
    }
}
