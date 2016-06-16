<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\Recipient;

class RecipientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $first
     * @param array $second
     * @param boolean $expected
     * @dataProvider recipientProvider
     */
    public function testEquality(array $first, array $second, $expected)
    {
        $firstRecipient = Factory::createRecipient($first[0], $first[1]);
        $secondRecipient = Factory::createRecipient($second[0], $second[1]);
        $this->assertEquals($expected, $firstRecipient->equals($secondRecipient));
    }

    public function recipientProvider()
    {
        return [
            [['name', 'email'], ['name', 'email'], true],
            [['name', 'email'], ['name1', 'email'], false],
            [['name', 'email'], ['name', 'email1'], false],
        ];
    }

    public function testToAndFromArray()
    {
        $original = Factory::createRecipient('name', 'email');
        $array = $original->toArray();

        /** @var Recipient $clone */
        $clone = Recipient::fromArray($array);

        $this->assertTrue($clone instanceof Recipient);
        $this->assertTrue($clone->equals($original));
    }
}
