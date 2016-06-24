<?php

namespace HelloFresh\Mailer\Implementation\Common;

use HelloFresh\Mailer\Contract\VariableInterface;

class Variable implements VariableInterface
{
    use ArrayValidatorTrait;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $value
     */
    private $value;

    /**
     * Variable constructor.
     * @param string $name
     * @param string $value
     */
    public function __construct($name = null, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
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
    public function toArray()
    {
        return [
            $this->getName(),
            $this->getValue(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $array)
    {
        static::validateArray(static::getArrayDefinition(), $array);

        $var = new static;
        $var->setName($array[0]);
        $var->setValue($array[1]);
        return $var;
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
