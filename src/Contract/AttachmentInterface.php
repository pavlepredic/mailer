<?php

namespace HelloFresh\Mailer\Contract;

interface AttachmentInterface extends EquatableInterface
{
    /**
     * @return string
     */
    public function getMimeType();

    /**
     * @return string
     */
    public function getName();

    /**
     * Content as a base64-encoded string
     * @return string
     */
    public function getContent();

    /**
     * @param string $mimeType
     * @return AttachmentInterface
     */
    public function setMimeType($mimeType);

    /**
     * @param string $name
     * @return AttachmentInterface
     */
    public function setName($name);

    /**
     * @param string $content - a base64-encoded string
     * @return AttachmentInterface
     */
    public function setContent($content);
}
