<?php

namespace HelloFresh\Mailer\Implementation\Common;

use HelloFresh\Mailer\Contract\AttachmentInterface;
use HelloFresh\Mailer\Contract\EquatableInterface;
use HelloFresh\Mailer\Contract\HeaderInterface;
use HelloFresh\Mailer\Contract\MessageInterface;
use HelloFresh\Mailer\Contract\PriorityInterface;
use HelloFresh\Mailer\Contract\RecipientInterface;
use HelloFresh\Mailer\Contract\SenderInterface;
use HelloFresh\Mailer\Implementation\Common\Priority\NormalPriority;
use HelloFresh\Mailer\Implementation\Common\Priority\Priority;

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
     * @var PriorityInterface $priority
     */
    private $priority;

    /**
     * Message constructor.
     * @param PriorityInterface $priority
     */
    public function __construct(PriorityInterface $priority = null)
    {
        $this->recipients = new Collection();
        $this->headers = new Collection();
        $this->attachments = new Collection();
        if (!$priority) {
            $priority = new NormalPriority();
        }

        $this->priority = $priority;
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
    public function hasRecipient(RecipientInterface $recipient)
    {
        return $this->getRecipients()->contains($recipient);
    }

    /**
     * {@inheritdoc}
     */
    public function addRecipient(RecipientInterface $recipient)
    {
        if (!$this->hasRecipient($recipient)) {
            $this->getRecipients()->add($recipient);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRecipient(RecipientInterface $recipient)
    {
        return $this->getRecipients()->removeElement($recipient);
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeader(HeaderInterface $header)
    {
        return $this->getHeaders()->contains($header);
    }

    /**
     * {@inheritdoc}
     */
    public function addHeader(HeaderInterface $header)
    {
        if (!$this->hasHeader($header)) {
            $this->getHeaders()->add($header);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeHeader(HeaderInterface $header)
    {
        $this->getHeaders()->removeElement($header);
    }

    /**
     * {@inheritdoc}
     */
    public function hasAttachment(AttachmentInterface $attachment)
    {
        return $this->getAttachments()->contains($attachment);
    }

    /**
     * {@inheritdoc}
     */
    public function addAttachment(AttachmentInterface $attachment)
    {
        if (!$this->hasAttachment($attachment)) {
            $this->getAttachments()->add($attachment);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAttachment(AttachmentInterface $attachment)
    {
        return $this->getAttachments()->removeElement($attachment);
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * {@inheritdoc}
     */
    public function setPriority(PriorityInterface $priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $recipients = [];
        foreach ($this->getRecipients() as $recipient) {
            $recipients[] = $recipient->toArray();
        }

        $headers = [];
        foreach ($this->getHeaders() as $header) {
            $headers[] = $header->toArray();
        }

        $attachments = [];
        foreach ($this->getAttachments() as $attachment) {
            $attachments[] = $attachment->toArray();
        }

        return [
            $this->getSubject(),
            $this->getContent(),
            $this->getSender()->toArray(),
            $recipients,
            $headers,
            $attachments,
            $this->getPriority()->toString(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $array)
    {
        //TODO validate array
        $message = new static;
        $message->setSubject($array[0]);
        $message->setContent($array[1]);
        $message->setSender(Sender::fromArray($array[2]));
        foreach ($array[3] as $recipient) {
            $message->addRecipient(Recipient::fromArray($recipient));
        }
        foreach ($array[4] as $header) {
            $message->addHeader(Header::fromArray($header));
        }
        foreach ($array[5] as $attachment) {
            $message->addAttachment(Attachment::fromArray($attachment));
        }
        $message->setPriority(Priority::fromString($array[6]));

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function equals(EquatableInterface $object)
    {
        return
            $object instanceof self
            and
            $this->getSubject() === $object->getSubject()
            and
            $this->getContent() === $object->getContent()
            and
            $this->getSender()->equals($object->getSender())
            and
            $this->getRecipients()->equals($object->getRecipients())
            and
            $this->getHeaders()->equals($object->getHeaders())
            and
            $this->getAttachments()->equals($object->getAttachments())
            and
            $this->getPriority()->equals($object->getPriority())
        ;
    }
}
