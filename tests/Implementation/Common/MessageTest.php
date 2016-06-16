<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\Attachment;
use HelloFresh\Mailer\Implementation\Common\Header;
use HelloFresh\Mailer\Implementation\Common\Message;
use HelloFresh\Mailer\Implementation\Common\Recipient;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testAddRemoveHeader()
    {
        $header1 = $this->createHeader('name1', 'value1');
        $header2 = $this->createHeader('name2', 'value2');

        $message = new Message();
        $message->addHeader($header1);
        $message->addHeader($header2);

        $this->assertTrue($message->hasHeader($header1));
        $this->assertTrue($message->hasHeader($header2));

        $message->removeHeader($header1);
        $this->assertFalse($message->hasHeader($header1));
        $this->assertTrue($message->hasHeader($header2));

        $message->removeHeader($header2);
        $this->assertFalse($message->hasHeader($header1));
        $this->assertFalse($message->hasHeader($header2));
    }

    public function testAddRemoveAttachment()
    {
        $attachment1 = $this->createAttachment('mime1', 'name1', 'content1');
        $attachment2 = $this->createAttachment('mime2', 'name2', 'content2');

        $message = new Message();
        $message->addAttachment($attachment1);
        $message->addAttachment($attachment2);

        $this->assertTrue($message->hasAttachment($attachment1));
        $this->assertTrue($message->hasAttachment($attachment2));

        $message->removeAttachment($attachment1);
        $this->assertFalse($message->hasAttachment($attachment1));
        $this->assertTrue($message->hasAttachment($attachment2));

        $message->removeAttachment($attachment2);
        $this->assertFalse($message->hasAttachment($attachment1));
        $this->assertFalse($message->hasAttachment($attachment2));
    }

    public function testAddRemoveRecipient()
    {
        $recipient1 = $this->createRecipient('name1', 'email1');
        $recipient2 = $this->createRecipient('name2', 'email2');

        $message = new Message();
        $message->addRecipient($recipient1);
        $message->addRecipient($recipient2);

        $this->assertTrue($message->hasRecipient($recipient1));
        $this->assertTrue($message->hasRecipient($recipient2));

        $message->removeRecipient($recipient1);
        $this->assertFalse($message->hasRecipient($recipient1));
        $this->assertTrue($message->hasRecipient($recipient2));

        $message->removeRecipient($recipient2);
        $this->assertFalse($message->hasRecipient($recipient1));
        $this->assertFalse($message->hasRecipient($recipient2));
    }

    private function createHeader($name, $value)
    {
        $header = new Header();
        $header->setName($name);
        $header->setValue($value);
        return $header;
    }

    private function createAttachment($mimeType, $name, $content)
    {
        $attachment = new Attachment();
        $attachment->setMimeType($mimeType);
        $attachment->setName($name);
        $attachment->setContent($content);
        return $attachment;
    }

    private function createRecipient($name, $email)
    {
        $recipient = new Recipient();
        $recipient->setName($name);
        $recipient->setEmail($email);
        return $recipient;
    }
}
