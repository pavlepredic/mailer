<?php

namespace HelloFresh\Mailer\Contract;

interface Header
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
     * @return void
     */
    public function setName($name);

    /**
     * @param string $value
     * @return string
     */
    public function setValue($value);
}
