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
}
