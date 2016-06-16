<?php

namespace HelloFresh\Mailer\Contract;

interface Header extends Equatable
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
     * @return Header
     */
    public function setName($name);

    /**
     * @param string $value
     * @return Header
     */
    public function setValue($value);
}
