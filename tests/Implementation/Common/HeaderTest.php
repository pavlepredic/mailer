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
}
