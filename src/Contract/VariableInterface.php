<?php

namespace HelloFresh\Mailer\Contract;

interface VariableInterface extends ArrayableInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string $name
     * @return HeaderInterface
     */
    public function setName($name);

    /**
     * @param string $value
     * @return HeaderInterface
     */
    public function setValue($value);
}
