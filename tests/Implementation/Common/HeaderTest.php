<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\Header;

class HeaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $first
     * @param array $second
     * @param boolean $expected
     * @dataProvider headerProvider
     */
    public function testEquality(array $first, array $second, $expected)
    {
        $firstHeader = Factory::createHeader($first[0], $first[1]);
        $secondHeader = Factory::createHeader($second[0], $second[1]);
        $this->assertEquals($expected, $firstHeader->equals($secondHeader));
    }

    public function headerProvider()
    {
        return [
            [['name', 'value'], ['name', 'value'], true],
            [['name', 'value'], ['name1', 'value'], false],
            [['name', 'value'], ['name', 'value1'], false],
        ];
    }

    public function testToAndFromArray()
    {
        $original = Factory::createHeader();
        $array = $original->toArray();

        /** @var Header $clone */
        $clone = Header::fromArray($array);

        $this->assertTrue($clone instanceof Header);
        $this->assertTrue($clone->equals($original));
    }
}
