<?php

namespace HelloFresh\Mailer\Implementation\Common;

use Doctrine\Common\Collections\ArrayCollection;
use HelloFresh\Mailer\Contract\AttachmentInterface;
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
     * @var string $htmlContent
     */
    private $htmlContent;

    /**
     * @var string $plainTextContent
     */
    private $plainTextContent;

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
        $this->recipients = new ArrayCollection();
        $this->headers = new ArrayCollection();
        $this->attachments = new ArrayCollection();
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
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHtmlContent()
    {
        return $this->htmlContent;
    }

    /**
     * {@inheritdoc}
     */
    public function setHtmlContent($htmlContent)
    {
        $this->htmlContent = $htmlContent;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPlainTextContent()
    {
        return $this->plainTextContent;
    }

    /**
     * {@inheritdoc}
     */
    public function setPlainTextContent($plainTextContent)
    {
        $this->plainTextContent = $plainTextContent;

        return $this;
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
    public function setSender(SenderInterface $sender)
    {
        $this->sender = $sender;

        return $this;
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
    public function addRecipient(RecipientInterface $recipient)
    {
        $this->getRecipients()->add($recipient);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRecipientByEmail($email)
    {
        foreach ($this->getRecipients() as $recipient) {
            if ($recipient->getEmail() === $email) {
                return $recipient;
            }
        }
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
    public function addHeader(HeaderInterface $header)
    {
        $this->getHeaders()->add($header);

        return $this;
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
    public function addAttachment(AttachmentInterface $attachment)
    {
        $this->getAttachments()->add($attachment);

        return $this;
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
            $this->getHtmlContent(),
            $this->getPlainTextContent(),
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
        $message->setHtmlContent($array[1]);
        $message->setPlainTextContent($array[2]);
        $message->setSender(Sender::fromArray($array[3]));
        foreach ($array[4] as $recipient) {
            $message->addRecipient(Recipient::fromArray($recipient));
        }
        foreach ($array[5] as $header) {
            $message->addHeader(Header::fromArray($header));
        }
        foreach ($array[6] as $attachment) {
            $message->addAttachment(Attachment::fromArray($attachment));
        }
        $message->setPriority(Priority::fromString($array[7]));

        return $message;
    }
}
