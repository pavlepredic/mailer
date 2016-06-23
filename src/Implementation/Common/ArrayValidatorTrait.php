<?php

namespace HelloFresh\Mailer\Implementation\Common;

use HelloFresh\Mailer\Exception\InvalidTypeException;
use HelloFresh\Mailer\Exception\SerializationException;
use HelloFresh\Mailer\Helper\Type;

trait ArrayValidatorTrait
{
    /**
     * Asserts that all types in $arrayDefinition are
     * present in the $inputArray
     * @param array $arrayDefinition
     * @param array $inputArray
     * @throws SerializationException
     */
    public static function validateArray(array $arrayDefinition, array $inputArray)
    {
        try {
            foreach ($arrayDefinition as $key => $type) {
                if (!array_key_exists($key, $inputArray)) {
                    throw new SerializationException("Missing key $key");
                }

                if ($inputArray[$key] !== null) {
                    Type::assertType($type, $inputArray[$key]);
                }
            }
        } catch (InvalidTypeException $e) {
            throw new SerializationException($e->getMessage());
        }
    }
}
