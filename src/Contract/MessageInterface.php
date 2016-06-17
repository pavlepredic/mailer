<?php

namespace HelloFresh\Mailer\Contract;

interface MessageInterface extends ArrayableInterface
{
    /**
     * @return string
     */
    public function getSubject();

    /**
     * @param string $subject
     * @return void
     */
    public function setSubject($subject);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $content
     * @return MessageInterface
     */
    public function setContent($content);

    /**
     * @return SenderInterface
     */
    public function getSender();

    /**
     * @param SenderInterface $sender
     * @return MessageInterface
     */
    public function setSender(SenderInterface $sender);

    /**
     * @return RecipientInterface[]
     */
    public function getRecipients();

    /**
     * @param RecipientInterface $recipient
     * @return MessageInterface
     */
    public function addRecipient(RecipientInterface $recipient);

    /**
     * @return HeaderInterface[]
     */
    public function getHeaders();

    /**
     * @param HeaderInterface $header
     * @return MessageInterface
     */
    public function addHeader(HeaderInterface $header);

    /**
     * @return AttachmentInterface[]
     */
    public function getAttachments();

    /**
     * @param AttachmentInterface $attachment
     * @return MessageInterface
     */
    public function addAttachment(AttachmentInterface $attachment);

    /**
     * @return PriorityInterface
     */
    public function getPriority();

    /**
     * @param PriorityInterface $priority
     * @return MessageInterface
     */
    public function setPriority(PriorityInterface $priority);
}
