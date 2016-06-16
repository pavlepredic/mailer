<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\Message;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testAddRemoveHeader()
    {
        $message = new Message();
        $message->addHeader(Factory::createHeader('name1', 'value1'));
        $message->addHeader(Factory::createHeader('name2', 'value2'));

        $this->assertTrue($message->hasHeader(Factory::createHeader('name1', 'value1')));
        $this->assertTrue($message->hasHeader(Factory::createHeader('name2', 'value2')));

        $message->removeHeader(Factory::createHeader('name1', 'value1'));
        $this->assertFalse($message->hasHeader(Factory::createHeader('name1', 'value1')));
        $this->assertTrue($message->hasHeader(Factory::createHeader('name2', 'value2')));

        $message->removeHeader(Factory::createHeader('name2', 'value2'));
        $this->assertFalse($message->hasHeader(Factory::createHeader('name1', 'value1')));
        $this->assertFalse($message->hasHeader(Factory::createHeader('name2', 'value2')));
    }

    public function testAddRemoveAttachment()
    {
        $message = new Message();
        $message->addAttachment(Factory::createAttachment('mime1', 'name1', 'content1'));
        $message->addAttachment(Factory::createAttachment('mime2', 'name2', 'content2'));

        $this->assertTrue($message->hasAttachment(Factory::createAttachment('mime1', 'name1', 'content1')));
        $this->assertTrue($message->hasAttachment(Factory::createAttachment('mime2', 'name2', 'content2')));

        $message->removeAttachment(Factory::createAttachment('mime1', 'name1', 'content1'));
        $this->assertFalse($message->hasAttachment(Factory::createAttachment('mime1', 'name1', 'content1')));
        $this->assertTrue($message->hasAttachment(Factory::createAttachment('mime2', 'name2', 'content2')));

        $message->removeAttachment(Factory::createAttachment('mime2', 'name2', 'content2'));
        $this->assertFalse($message->hasAttachment(Factory::createAttachment('mime1', 'name1', 'content1')));
        $this->assertFalse($message->hasAttachment(Factory::createAttachment('mime2', 'name2', 'content2')));
    }

    public function testAddRemoveRecipient()
    {
        $message = new Message();
        $message->addRecipient(Factory::createRecipient('name1', 'email1'));
        $message->addRecipient(Factory::createRecipient('name2', 'email2'));

        $this->assertTrue($message->hasRecipient(Factory::createRecipient('name1', 'email1')));
        $this->assertTrue($message->hasRecipient(Factory::createRecipient('name2', 'email2')));

        $message->removeRecipient(Factory::createRecipient('name1', 'email1'));
        $this->assertFalse($message->hasRecipient(Factory::createRecipient('name1', 'email1')));
        $this->assertTrue($message->hasRecipient(Factory::createRecipient('name2', 'email2')));

        $message->removeRecipient(Factory::createRecipient('name2', 'email2'));
        $this->assertFalse($message->hasRecipient(Factory::createRecipient('name1', 'email1')));
        $this->assertFalse($message->hasRecipient(Factory::createRecipient('name2', 'email2')));
    }

    public function testToAndFromArray()
    {
        $original = Factory::createMessage();

        $array = $original->toArray();

        /** @var Message $clone */
        $clone = Message::fromArray($array);

        $this->assertTrue($clone instanceof Message);
        $this->assertTrue($original->equals($clone));
    }
}
