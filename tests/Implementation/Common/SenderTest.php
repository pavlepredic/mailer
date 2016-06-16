<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\Sender;

class SenderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $first
     * @param array $second
     * @param boolean $expected
     * @dataProvider senderProvider
     */
    public function testEquality(array $first, array $second, $expected)
    {
        $firstSender = Factory::createSender($first[0], $first[1]);
        $secondSender = Factory::createSender($second[0], $second[1]);
        $this->assertEquals($expected, $firstSender->equals($secondSender));
    }

    public function senderProvider()
    {
        return [
            [['name', 'email'], ['name', 'email'], true],
            [['name', 'email'], ['name1', 'email'], false],
            [['name', 'email'], ['name', 'email1'], false],
        ];
    }

    public function testToAndFromArray()
    {
        $original = Factory::createSender('name', 'email');
        $array = $original->toArray();

        /** @var Sender $clone */
        $clone = Sender::fromArray($array);

        $this->assertTrue($clone instanceof Sender);
        $this->assertTrue($clone->equals($original));
    }
}
