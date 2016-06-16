<?php

namespace HelloFresh\Mailer\Implementation\Common;

use HelloFresh\Mailer\Contract\Attachment;
use HelloFresh\Mailer\Contract\Equatable;
use HelloFresh\Mailer\Contract\Header;
use HelloFresh\Mailer\Contract\Message as MessageContract;
use HelloFresh\Mailer\Contract\Recipient;
use HelloFresh\Mailer\Contract\Sender;

class Message implements MessageContract
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
     * @var Sender $sender
     */
    private $sender;

    /**
     * @var Recipient[] $recipients
     */
    private $recipients;

    /**
     * @var Header[] $headers
     */
    private $headers;

    /**
     * @var Attachment[] $attachments
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
    public function setSender(Sender $sender)
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
    public function hasRecipient(Recipient $recipient)
    {
        return $this->contains($this->getRecipients(), $recipient);
    }

    /**
     * {@inheritdoc}
     */
    public function addRecipient(Recipient $recipient)
    {
        if (!$this->hasRecipient($recipient)) {
            $this->recipients[] = $recipient;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRecipient(Recipient $recipient)
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
    public function hasHeader(Header $header)
    {
        return $this->contains($this->getHeaders(), $header);
    }

    /**
     * {@inheritdoc}
     */
    public function addHeader(Header $header)
    {
        if (!$this->hasHeader($header)) {
            $this->headers[] = $header;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeHeader(Header $header)
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
    public function hasAttachment(Attachment $attachment)
    {
        return $this->contains($this->getAttachments(), $attachment);
    }

    /**
     * {@inheritdoc}
     */
    public function addAttachment(Attachment $attachment)
    {
        if (!$this->hasAttachment($attachment)) {
            $this->attachments[] = $attachment;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAttachment(Attachment $attachment)
    {
        return $this->setAttachments($this->filter($this->getAttachments(), $attachment));
    }

    /**
     * Checks if $object is contained in the array of $objects
     * @param array $objects
     * @param Equatable $object
     * @return bool
     */
    private function contains(array $objects, Equatable $object)
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
     * @param Equatable $object
     * @return array
     */
    private function filter(array $objects, Equatable $object)
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
