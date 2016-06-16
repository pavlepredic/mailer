<?php

namespace HelloFresh\Mailer\Implementation\Common;

use HelloFresh\Mailer\Contract\EquatableInterface;
use HelloFresh\Mailer\Contract\HeaderInterface;

class Header implements HeaderInterface
{
    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $value
     */
    private $value;

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
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        $this->value = $value;

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
            $this->getValue() === $object->getValue()
        ;
    }
}
