<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\Message;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testToAndFromArray()
    {
        $original = Factory::createMessage();

        $array = $original->toArray();

        /** @var Message $clone */
        $clone = Message::fromArray($array);

        $this->assertTrue($clone instanceof Message);
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
        $original = Factory::createMessage();
        $array = $original->toArray();

        $array[$arrayKey] = $replacement;
        Message::fromArray($array);
    }

    public function arrayValidationDataProvider()
    {
        return [
            [0, 1],
            [1, 1],
            [2, 1],
            [3, 1],
            [4, 1],
            [5, 1],
            [6, 1],
            [7, 1],
            [8, 1],
            [9, 1],
            [10, 1],
        ];
    }
}
