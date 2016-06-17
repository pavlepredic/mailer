<?php

namespace HelloFresh\Mailer\Implementation\Common\Priority;

use HelloFresh\Mailer\Contract\PriorityInterface;
use HelloFresh\Mailer\Exception\InvalidArgumentException;

abstract class Priority implements PriorityInterface
{
    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        $rc = new \ReflectionClass($this);
        $name = lcfirst($rc->getShortName());
        return strtolower(preg_replace('/(\p{Lu})/', '_$1', $name));
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString($string)
    {
        $tokenized = explode('_', $string);
        $tokenized = array_map('ucfirst', $tokenized);
        $class = __NAMESPACE__ . '\\' . join('', $tokenized);
        if (!class_exists($class)) {
            throw new InvalidArgumentException("Priority $string not defined");
        }

        return new $class;
    }
}
