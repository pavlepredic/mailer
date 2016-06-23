<?php

namespace Tests\Helper;

use HelloFresh\Mailer\Helper\Type;
use HelloFresh\Mailer\Implementation\Common\Header;

class TypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider typeProvider
     */
    public function testAssertType($type, $variable)
    {
        Type::assertType($type, $variable);
    }

    /**
     * @dataProvider invalidTypeProvider
     * @expectedException HelloFresh\Mailer\Exception\InvalidTypeException
     */
    public function testAssertInvalidType($type, $variable)
    {
        Type::assertType($type, $variable);
    }

    public function typeProvider()
    {
        return [
            ["boolean", true],
            ["integer", 1],
            ["double", 1.0],
            ["string", "string"],
            ["array", []],
            ["object", new \stdClass()],
            ["resource", curl_init()],
            ["NULL", null],
            [Header::class, new Header()],

            //nullable types
            ["string", null],
            ["object", null],
            [Header::class, null],
        ];
    }

    public function invalidTypeProvider()
    {
        return [
            ["boolean", 1],
            ["integer", "1"],
            ["double", "1.0"],
            ["string", 1],
            ["array", new \stdClass()],
            ["object", []],
            ["resource", new \stdClass()],
            ["NULL", false],
            [Header::class, new \stdClass()],

            //non-nullable types
            ["boolean", null],
            ["integer", null],
            ["double", null],
            ["array", null],
            ["resource", null],
        ];
    }
}
