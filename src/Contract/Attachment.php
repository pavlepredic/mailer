<?php

namespace HelloFresh\Mailer\Contract;

interface Attachment extends Equatable
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
     * @return Attachment
     */
    public function setMimeType($mimeType);

    /**
     * @param string $name
     * @return Attachment
     */
    public function setName($name);

    /**
     * @param string $content - a base64-encoded string
     * @return Attachment
     */
    public function setContent($content);
}
