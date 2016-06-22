<?php

namespace HelloFresh\Mailer\Implementation\Mandrill;

use HelloFresh\Mailer\Contract\MessageInterface;

class MessageDecorator
{
    /**
     * @var MessageInterface $message
     */
    private $message;

    /**
     * Message constructor.
     * @param MessageInterface $message
     */
    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    /**
     * @return array
     */
    protected function getRecipients()
    {
        $recipient = $this->message->getRecipient();
        return [
            [
                'email' => $recipient->getEmail(),
                'name' => $recipient->getName(),
                'type' => $recipient->getType(),
            ]
        ];
    }

    /**
     * @return array
     */
    protected function getHeaders()
    {
        $headers = [];
        foreach ($this->message->getHeaders() as $header) {
            $headers[$header->getName()] = $header->getValue();
        }

        return $headers;
    }

    /**
     * @return array
     */
    protected function getAttachments()
    {
        $attachments = [];
        foreach ($this->message->getAttachments() as $attachment) {
            $attachments[] = [
                'type' => $attachment->getMimeType(),
                'name' => $attachment->getName(),
                'content' => $attachment->getContent(),
            ];
        }

        return $attachments;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'html' => $this->message->getHtmlContent(),
            'text' => $this->message->getPlainTextContent(),
            'subject' => $this->message->getSubject(),
            'from_email' => $this->message->getSender()->getEmail(),
            'from_name' => $this->message->getSender()->getName(),
            'to' => $this->getRecipients(),
            'headers' => $this->getHeaders(),
            'attachments' => $this->getAttachments(),
            'global_merge_vars' => $this->getMergeVariables(),
        ];
    }

    /**
     * @return array
     */
    public function getMergeVariables()
    {
        $variables = [];
        foreach ($this->message->getVariables() as $variable) {
            $variables[] = [
                'name' => $variable->getName(),
                'content' => $variable->getValue(),
            ];
        }
        return $variables;
    }
}
