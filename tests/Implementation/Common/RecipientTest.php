<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\Recipient;

class RecipientTest extends \PHPUnit_Framework_TestCase
{
    public function testToAndFromArray()
    {
        $original = Factory::createRecipient();
        $array = $original->toArray();

        /** @var Recipient $clone */
        $clone = Recipient::fromArray($array);

        $this->assertTrue($clone instanceof Recipient);
        $this->assertEquals($original, $clone);
    }
}
