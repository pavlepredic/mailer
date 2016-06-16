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
        $this->assertEquals($expected, $this->loadAttachment($first)->equals($this->loadAttachment($second)));
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

    private function loadAttachment(array $data)
    {
        $attachment = new Attachment();
        $attachment->setMimeType($data[0]);
        $attachment->setName($data[1]);
        $attachment->setContent($data[2]);
        return $attachment;
    }
}
