<?php

namespace HelloFresh\Mailer\Implementation\Common;

use HelloFresh\Mailer\Contract\ParticipantInterface;

class Participant implements ParticipantInterface
{
    use ArrayValidatorTrait;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $email
     */
    private $email;

    /**
     * Participant constructor.
     * @param string $name
     * @param string $email
     */
    public function __construct($email = null, $name = null)
    {
        $this->name = $name;
        $this->email = $email;
    }

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
    public function toArray()
    {
        return [
            $this->getName(),
            $this->getEmail(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $array)
    {
        static::validateArray(static::getArrayDefinition(), $array);

        $participant = new static;
        $participant->setName($array[0]);
        $participant->setEmail($array[1]);
        return $participant;
    }

    /**
     * {@inheritdoc}
     */
    public static function getArrayDefinition()
    {
        return [
            'string',
            'string',
        ];
    }
}
