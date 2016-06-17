<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\Attachment;

class AttachmentTest extends \PHPUnit_Framework_TestCase
{
    public function testToAndFromArray()
    {
        $original = Factory::createAttachment('mime', 'name', 'content');
        $array = $original->toArray();

        /** @var Attachment $clone */
        $clone = Attachment::fromArray($array);

        $this->assertTrue($clone instanceof Attachment);
        $this->assertEquals($clone, $original);
    }
}
