<?php

namespace Tests\Implementation\Mandrill;

use HelloFresh\Mailer\Implementation\Mandrill\MessageDecorator;
use Tests\Implementation\Common\Factory;

class MessageDecoratorTest extends \PHPUnit_Framework_TestCase
{
    public function testToArray()
    {
        $message = Factory::createMessage();
        $decorator = new MessageDecorator($message);
        $array = $decorator->toArray();

        $this->assertEquals($message->getHtmlContent(), $array['html']);
        $this->assertEquals($message->getPlainTextContent(), $array['text']);
        $this->assertEquals($message->getSubject(), $array['subject']);
        $this->assertEquals($message->getSender()->getEmail(), $array['from_email']);
        $this->assertEquals($message->getSender()->getName(), $array['from_name']);

        $to = [
            [
                'email' => $message->getRecipient()->getEmail(),
                'name' => $message->getRecipient()->getName(),
                'type' => $message->getRecipient()->getType(),
            ]
        ];

        $this->assertEquals($to, $array['to']);

        $headers = [];
        foreach ($message->getHeaders() as $header) {
            $headers[$header->getName()] = $header->getValue();
        }

        $this->assertEquals($headers, $array['headers']);

        $attachments = [];
        foreach ($message->getAttachments() as $attachment) {
            $attachments[] = [
                'type' => $attachment->getMimeType(),
                'name' => $attachment->getName(),
                'content' => $attachment->getContent(),
            ];
        }

        $this->assertEquals($attachments, $array['attachments']);

        $mergeVariables = [];
        foreach ($message->getVariables() as $variable) {
            $mergeVariables[] = [
                'name' => $variable->getName(),
                'content' => $variable->getValue(),
            ];
        }

        $this->assertEquals($mergeVariables, $array['global_merge_vars']);
    }
}
