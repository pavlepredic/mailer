<?php

namespace HelloFresh\Mailer\Contract;

use HelloFresh\Mailer\Implementation\Common\Variable;

interface MessageInterface extends ArrayableInterface
{
    /**
     * @return string
     */
    public function getSubject();

    /**
     * @param string $subject
     * @return MessageInterface
     */
    public function setSubject($subject);

    /**
     * @return string
     */
    public function getTemplate();

    /**
     * @param string $template
     * @return MessageInterface
     */
    public function setTemplate($template);

    /**
     * @return Variable[]
     */
    public function getVariables();

    /**
     * @param Variable $variable
     * @return MessageInterface
     */
    public function addVariable(Variable $variable);

    /**
     * @return MessageInterface
     */
    public function clearVariables();

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
     * To simplify error handling, first release
     * will support only one recipient per message
     * @return RecipientInterface
     */
    public function getRecipient();

    /**
     * @param RecipientInterface $recipient
     * @return MessageInterface
     */
    public function setRecipient(RecipientInterface $recipient);

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
     * @return MessageInterface
     */
    public function clearHeaders();

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
     * @return MessageInterface
     */
    public function clearAttachments();

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
