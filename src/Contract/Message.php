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
     * @return Header[]
     */
    public function getHeaders();

    /**
     * @return Attachment[]
     */
    public function getAttachments();

    /**
     * @param string $subject
     * @return void
     */
    public function setSubject($subject);

    /**
     * @param string $content
     * @return void
     */
    public function setContent($content);

    /**
     * @param Sender $sender
     * @return void
     */
    public function setSender(Sender $sender);

    /**
     * @param Recipient[] $recipients
     * @return void
     */
    public function setRecipients(array $recipients);

    /**
     * @param Recipient $recipient
     * @return void
     */
    public function addRecipient(Recipient $recipient);

    /**
     * @param Recipient $recipient
     * @return void
     */
    public function removeRecipient(Recipient $recipient);

    /**
     * @param Header[] $headers
     * @return void
     */
    public function setHeaders(array $headers);

    /**
     * @param Header $header
     * @return void
     */
    public function addHeader(Header $header);

    /**
     * @param Header $header
     * @return void
     */
    public function removeHeader(Header $header);

    /**
     * @param Attachment[] $attachments
     * @return void
     */
    public function setAttachments(array $attachments);

    /**
     * @param Attachment $attachment
     * @return void
     */
    public function addAttachment(Attachment $attachment);

    /**
     * @param Attachment $attachment
     * @return void
     */
    public function removeAttachment(Attachment $attachment);
}
