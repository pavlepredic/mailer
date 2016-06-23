<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\Header;

class HeaderTest extends \PHPUnit_Framework_TestCase
{
    public function testToAndFromArray()
    {
        $original = Factory::createHeader();
        $array = $original->toArray();

        /** @var Header $clone */
        $clone = Header::fromArray($array);

        $this->assertTrue($clone instanceof Header);
        $this->assertEquals($original, $clone);
    }

    /**
     * @expectedException HelloFresh\Mailer\Exception\SerializationException
     * @dataProvider arrayValidationDataProvider
     * @param int $arrayKey
     * @param mixed $replacement
     */
    public function testArrayValidation($arrayKey, $replacement)
    {
        $original = Factory::createHeader();
        $array = $original->toArray();

        $array[$arrayKey] = $replacement;
        Header::fromArray($array);
    }

    public function arrayValidationDataProvider()
    {
        return [
            [0, 1],
            [1, 1],
        ];
    }
}
