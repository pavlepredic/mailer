<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\Attachment;

class AttachmentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $first
     * @param array $second
     * @param boolean $expected
     * @dataProvider attachmentProvider
     */
    public function testEquality(array $first, array $second, $expected)
    {
        $firstAttachment = Factory::createAttachment($first[0], $first[1], $first[2]);
        $secondAttachment = Factory::createAttachment($second[0], $second[1], $second[2]);
        $this->assertEquals($expected, $firstAttachment->equals($secondAttachment));
    }

    public function attachmentProvider()
    {
        return [
            [['mime', 'name', 'content'], ['mime', 'name', 'content'], true],
            [['mime', 'name', 'content'], ['mime1', 'name', 'content'], false],
            [['mime', 'name', 'content'], ['mime', 'name1', 'content'], false],
            [['mime', 'name', 'content'], ['mime', 'name', 'content1'], false],
        ];
    }

    public function testToAndFromArray()
    {
        $original = Factory::createAttachment('mime', 'name', 'content');
        $array = $original->toArray();

        /** @var Attachment $clone */
        $clone = Attachment::fromArray($array);

        $this->assertTrue($clone instanceof Attachment);
        $this->assertTrue($clone->equals($original));
    }
}
