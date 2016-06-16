<?php

namespace HelloFresh\Mailer\Contract;

interface Message
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
     * @return Sender
     */
    public function getSender();

    /**
     * @return Recipient[]
     */
    public function getRecipients();

    /**
     * @param Recipient $recipient
     * @return boolean
     */
    public function hasRecipient(Recipient $recipient);

    /**
     * @return Header[]
     */
    public function getHeaders();

    /**
     * @param Header $header
     * @return boolean
     */
    public function hasHeader(Header $header);

    /**
     * @return Attachment[]
     */
    public function getAttachments();

    /**
     * @param Attachment $attachment
     * @return boolean
     */
    public function hasAttachment(Attachment $attachment);

    /**
     * @param string $subject
     * @return void
     */
    public function setSubject($subject);

    /**
     * @param string $content
     * @return Message
     */
    public function setContent($content);

    /**
     * @param Sender $sender
     * @return Message
     */
    public function setSender(Sender $sender);

    /**
     * @param Recipient[] $recipients
     * @return Message
     */
    public function setRecipients(array $recipients);

    /**
     * @param Recipient $recipient
     * @return Message
     */
    public function addRecipient(Recipient $recipient);

    /**
     * @param Recipient $recipient
     * @return Message
     */
    public function removeRecipient(Recipient $recipient);

    /**
     * @param Header[] $headers
     * @return Message
     */
    public function setHeaders(array $headers);

    /**
     * @param Header $header
     * @return Message
     */
    public function addHeader(Header $header);

    /**
     * @param Header $header
     * @return Message
     */
    public function removeHeader(Header $header);

    /**
     * @param Attachment[] $attachments
     * @return Message
     */
    public function setAttachments(array $attachments);

    /**
     * @param Attachment $attachment
     * @return Message
     */
    public function addAttachment(Attachment $attachment);

    /**
     * @param Attachment $attachment
     * @return Message
     */
    public function removeAttachment(Attachment $attachment);
}
