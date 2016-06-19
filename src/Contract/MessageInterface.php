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
    public function getHtmlContent();

    /**
     * @param string $htmlContent
     * @return MessageInterface
     */
    public function setHtmlContent($htmlContent);

    /**
     * @return string
     */
    public function getPlainTextContent();

    /**
     * @param string $plainTextContent
     * @return MessageInterface
     */
    public function setPlainTextContent($plainTextContent);

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
     * @param string $email
     * @return RecipientInterface
     */
    public function getRecipientByEmail($email);

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
