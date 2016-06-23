<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\Sender;

class SenderTest extends \PHPUnit_Framework_TestCase
{
    public function testToAndFromArray()
    {
        $original = Factory::createSender();
        $array = $original->toArray();

        /** @var Sender $clone */
        $clone = Sender::fromArray($array);

        $this->assertTrue($clone instanceof Sender);
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
        $original = Factory::createSender();
        $array = $original->toArray();

        $array[$arrayKey] = $replacement;
        Sender::fromArray($array);
    }

    public function arrayValidationDataProvider()
    {
        return [
            [0, 1],
            [1, 1],
        ];
    }
}
