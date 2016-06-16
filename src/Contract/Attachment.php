<?php

namespace HelloFresh\Mailer\Contract;

interface Attachment
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
     * @return void
     */
    public function setMimeType($mimeType);

    /**
     * @param string $name
     * @return void
     */
    public function setName($name);

    /**
     * @param string $content - a base64-encoded string
     * @return void
     */
    public function setContent($content);
}
