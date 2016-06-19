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
     * @return MessageInterface
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param MessageInterface $message
     * @return MessageDecorator
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return array
     */
    protected function getRecipients()
    {
        $recipient = $this->getMessage()->getRecipient();
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
        foreach ($this->getMessage()->getHeaders() as $header) {
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
        foreach ($this->getMessage()->getAttachments() as $attachment) {
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
            'html' => $this->getMessage()->getHtmlContent(),
            'text' => $this->getMessage()->getPlainTextContent(),
            'subject' => $this->getMessage()->getSubject(),
            'from_email' => $this->getMessage()->getSender()->getEmail(),
            'from_name' => $this->getMessage()->getSender()->getName(),
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
