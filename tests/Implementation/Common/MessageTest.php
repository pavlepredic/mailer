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
}
