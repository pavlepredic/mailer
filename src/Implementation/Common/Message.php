<?php

namespace HelloFresh\Mailer\Implementation\Common;

use HelloFresh\Mailer\Contract\AttachmentInterface;
use HelloFresh\Mailer\Contract\EquatableInterface;
use HelloFresh\Mailer\Contract\HeaderInterface;
use HelloFresh\Mailer\Contract\MessageInterface;
use HelloFresh\Mailer\Contract\RecipientInterface;
use HelloFresh\Mailer\Contract\SenderInterface;

class Message implements MessageInterface
{
    /**
     * @var string $subject
     */
    private $subject;

    /**
     * @var string $content
     */
    private $content;

    /**
     * @var SenderInterface $sender
     */
    private $sender;

    /**
     * @var RecipientInterface[] $recipients
     */
    private $recipients;

    /**
     * @var HeaderInterface[] $headers
     */
    private $headers;

    /**
     * @var AttachmentInterface[] $attachments
     */
    private $attachments;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->recipients = [];
        $this->headers = [];
        $this->attachments = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * {@inheritdoc}
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * {@inheritdoc}
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSender(SenderInterface $sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRecipients(array $recipients)
    {
        $this->recipients = $recipients;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRecipient(RecipientInterface $recipient)
    {
        return $this->contains($this->getRecipients(), $recipient);
    }

    /**
     * {@inheritdoc}
     */
    public function addRecipient(RecipientInterface $recipient)
    {
        if (!$this->hasRecipient($recipient)) {
            $this->recipients[] = $recipient;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRecipient(RecipientInterface $recipient)
    {
        return $this->setRecipients($this->filter($this->getRecipients(), $recipient));
    }

    /**
     * {@inheritdoc}
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeader(HeaderInterface $header)
    {
        return $this->contains($this->getHeaders(), $header);
    }

    /**
     * {@inheritdoc}
     */
    public function addHeader(HeaderInterface $header)
    {
        if (!$this->hasHeader($header)) {
            $this->headers[] = $header;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeHeader(HeaderInterface $header)
    {
        $this->setHeaders($this->filter($this->getHeaders(), $header));
    }

    /**
     * {@inheritdoc}
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAttachment(AttachmentInterface $attachment)
    {
        return $this->contains($this->getAttachments(), $attachment);
    }

    /**
     * {@inheritdoc}
     */
    public function addAttachment(AttachmentInterface $attachment)
    {
        if (!$this->hasAttachment($attachment)) {
            $this->attachments[] = $attachment;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAttachment(AttachmentInterface $attachment)
    {
        return $this->setAttachments($this->filter($this->getAttachments(), $attachment));
    }

    /**
     * Checks if $object is contained in the array of $objects
     * @param array $objects
     * @param EquatableInterface $object
     * @return bool
     */
    private function contains(array $objects, EquatableInterface $object)
    {
        foreach ($objects as $ownObject) {
            if ($object->equals($ownObject)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Filters out $object from the array of $objects
     * @param array $objects
     * @param EquatableInterface $object
     * @return array
     */
    private function filter(array $objects, EquatableInterface $object)
    {
        $filtered = [];
        foreach ($objects as $ownObject) {
            if (!$object->equals($ownObject)) {
                $filtered[] = $ownObject;
            }
        }

        return $filtered;
    }
}
