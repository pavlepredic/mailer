<?php

namespace HelloFresh\Mailer\Contract;

use Doctrine\Common\Collections\Collection;

interface MessageInterface extends EquatableInterface, ArrayableInterface
{
    /**
     * @return string
     */
    public function getSubject();

    /**
     * @return string
     */
    public function getContent();

    /**
     * @return SenderInterface
     */
    public function getSender();

    /**
     * @return RecipientInterface[]
     */
    public function getRecipients();

    /**
     * @param RecipientInterface $recipient
     * @return boolean
     */
    public function hasRecipient(RecipientInterface $recipient);

    /**
     * @return HeaderInterface[]
     */
    public function getHeaders();

    /**
     * @param HeaderInterface $header
     * @return boolean
     */
    public function hasHeader(HeaderInterface $header);

    /**
     * @return AttachmentInterface[]
     */
    public function getAttachments();

    /**
     * @return PriorityInterface
     */
    public function getPriority();

    /**
     * @param AttachmentInterface $attachment
     * @return boolean
     */
    public function hasAttachment(AttachmentInterface $attachment);

    /**
     * @param string $subject
     * @return void
     */
    public function setSubject($subject);

    /**
     * @param string $content
     * @return MessageInterface
     */
    public function setContent($content);

    /**
     * @param SenderInterface $sender
     * @return MessageInterface
     */
    public function setSender(SenderInterface $sender);

    /**
     * @param RecipientInterface $recipient
     * @return MessageInterface
     */
    public function addRecipient(RecipientInterface $recipient);

    /**
     * @param RecipientInterface $recipient
     * @return MessageInterface
     */
    public function removeRecipient(RecipientInterface $recipient);

    /**
     * @param HeaderInterface $header
     * @return MessageInterface
     */
    public function addHeader(HeaderInterface $header);

    /**
     * @param HeaderInterface $header
     * @return MessageInterface
     */
    public function removeHeader(HeaderInterface $header);

    /**
     * @param AttachmentInterface $attachment
     * @return MessageInterface
     */
    public function addAttachment(AttachmentInterface $attachment);

    /**
     * @param AttachmentInterface $attachment
     * @return MessageInterface
     */
    public function removeAttachment(AttachmentInterface $attachment);

    /**
     * @param PriorityInterface $priority
     * @return MessageInterface
     */
    public function setPriority(PriorityInterface $priority);
}
