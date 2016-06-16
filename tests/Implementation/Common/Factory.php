<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\Attachment;
use HelloFresh\Mailer\Implementation\Common\Header;
use HelloFresh\Mailer\Implementation\Common\Message;
use HelloFresh\Mailer\Implementation\Common\Priority\HighPriority;
use HelloFresh\Mailer\Implementation\Common\Priority\Priority;
use HelloFresh\Mailer\Implementation\Common\Recipient;
use HelloFresh\Mailer\Implementation\Common\Sender;

class Factory
{
    public static function createHeader($name = 'headerName', $value = 'headerValue')
    {
        $header = new Header();
        $header->setName($name);
        $header->setValue($value);
        return $header;
    }

    public static function createAttachment($mimeType = 'attachmentType', $name = 'attachmentName', $content = 'attachmentContent')
    {
        $attachment = new Attachment();
        $attachment->setMimeType($mimeType);
        $attachment->setName($name);
        $attachment->setContent($content);
        return $attachment;
    }

    public static function createRecipient($name = 'recipientName', $email = 'recipientEmail')
    {
        $recipient = new Recipient();
        $recipient->setName($name);
        $recipient->setEmail($email);
        return $recipient;
    }

    public static function createSender($name = 'senderName', $email = 'senderEmail')
    {
        $sender = new Sender();
        $sender->setName($name);
        $sender->setEmail($email);
        return $sender;
    }

    public static function createMessage(
        $subject = 'messageSubject',
        $content = 'messageContent',
        $priority = 'high_priority',
        Sender $sender = null,
        array $recipients = [],
        array $headers = [],
        array $attachments = []
    ) {
        $message = new Message(Priority::fromString($priority));
        $message->setSubject($subject);
        $message->setContent($content);
        $message->setSender($sender ? $sender : Factory::createSender());
        if (!$recipients) {
            $recipients[] = Factory::createRecipient();
        }
        foreach ($recipients as $recipient) {
            $message->addRecipient($recipient);
        }
        if (!$headers) {
            $headers[] = Factory::createHeader();
        }
        foreach ($headers as $header) {
            $message->addHeader($header);
        }
        if (!$attachments) {
            $attachments[] = Factory::createAttachment();
        }
        foreach ($attachments as $attachment) {
            $message->addAttachment($attachment);
        }

        return $message;
    }
}
