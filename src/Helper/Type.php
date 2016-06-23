<?php

namespace HelloFresh\Mailer\Helper;

use HelloFresh\Mailer\Exception\InvalidTypeException;

class Type
{
    /**
     * Internal PHP types
     * @var array $internalTypes
     */
    protected static $internalTypes = [
        //type      => nullable
        "boolean"   => false,
        "integer"   => false,
        "double"    => false,
        "string"    => true,
        "array"     => false,
        "object"    => true,
        "resource"  => false,
        "NULL"      => true,
    ];

    /**
     * @param string $expectedType
     * @param mixed $variable
     * @return bool
     * @throws InvalidTypeException
     */
    public static function assertType($expectedType, $variable)
    {
        $actualType = gettype($variable);
        if (self::isInternalType($expectedType)) {
            if ($actualType !== $expectedType) {
                if (!($variable === null and self::isNullable($expectedType))) {
                    throw new InvalidTypeException(sprintf("Expected %s, got %s", $expectedType, $actualType));
                }
            }
        } elseif (!($variable instanceof $expectedType)) {
            if ($variable !== null) {
                throw new InvalidTypeException(sprintf("Expected %s, got %s", $expectedType, $actualType));
            }
        }
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isInternalType($type)
    {
        return in_array($type, array_keys(self::$internalTypes));
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isNullable($type)
    {
        return self::$internalTypes[$type];
    }
}
