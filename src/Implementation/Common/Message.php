<?php

namespace HelloFresh\Mailer\Implementation\Common;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use HelloFresh\Mailer\Contract\ArrayableInterface;
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
     * @var string $template
     */
    private $template;

    /**
     * @var Variable[] $variables
     */
    private $variables;

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
     * @var RecipientInterface $recipient
     */
    private $recipient;

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
     * @var SendAttempt[] $sendAttempts
     */
    private $sendAttempts;

    /**
     * Message constructor.
     * @param PriorityInterface $priority
     */
    public function __construct(PriorityInterface $priority = null)
    {
        $this->headers = new ArrayCollection();
        $this->attachments = new ArrayCollection();
        $this->variables = new ArrayCollection();
        $this->sendAttempts = new ArrayCollection();
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
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * {@inheritdoc}
     */
    public function addVariable(Variable $variable)
    {
        $this->getVariables()->add($variable);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clearVariables()
    {
        $this->variables->clear();

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
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * {@inheritdoc}
     */
    public function setRecipient(RecipientInterface $recipient)
    {
        $this->recipient = $recipient;

        return $this;
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
    public function clearHeaders()
    {
        $this->headers->clear();

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
    public function clearAttachments()
    {
        $this->attachments->clear();

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
    public function getSendAttempts()
    {
        return $this->sendAttempts;
    }

    /**
     * {@inheritdoc}
     */
    public function addSendAttempt(SendAttempt $sendAttempts)
    {
        $this->sendAttempts->add($sendAttempts);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clearSendAttempts()
    {
        $this->sendAttempts->clear();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function countSendAttempts()
    {
        return $this->sendAttempts->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getLastSendAttempt()
    {
        return $this->sendAttempts->last();
    }


    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            $this->getSubject(),
            $this->getTemplate(),
            $this->getHtmlContent(),
            $this->getPlainTextContent(),
            $this->getSender()->toArray(),
            $this->getRecipient()->toArray(),
            $this->convertCollectionToArray($this->getHeaders()),
            $this->convertCollectionToArray($this->getAttachments()),
            $this->convertCollectionToArray($this->getVariables()),
            $this->convertCollectionToArray($this->getSendAttempts()),
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
        $message->setTemplate($array[1]);
        $message->setHtmlContent($array[2]);
        $message->setPlainTextContent($array[3]);
        $message->setSender(Sender::fromArray($array[4]));
        $message->setRecipient(Recipient::fromArray($array[5]));

        foreach ($array[6] as $header) {
            $message->addHeader(Header::fromArray($header));
        }
        foreach ($array[7] as $attachment) {
            $message->addAttachment(Attachment::fromArray($attachment));
        }
        foreach ($array[8] as $variable) {
            $message->addVariable(Variable::fromArray($variable));
        }
        foreach ($array[9] as $sendAttempt) {
            $message->addSendAttempt(SendAttempt::fromArray($sendAttempt));
        }
        $message->setPriority(Priority::fromString($array[10]));

        return $message;
    }

    /**
     * Converts a Collection of ArrayableInterface elements to an array
     * @param Collection $collection
     * @return array
     */
    private function convertCollectionToArray(Collection $collection)
    {
        $array = [];
        /** @var ArrayableInterface $item */
        foreach ($collection as $item) {
            $array[] = $item->toArray();
        }
        return $array;
    }
}
