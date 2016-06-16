<?php

namespace HelloFresh\Mailer\Implementation\Common;

use HelloFresh\Mailer\Contract\Attachment as AttachmentContract;
use HelloFresh\Mailer\Contract\Equatable;

class Attachment implements AttachmentContract
{
    /**
     * @var string $mimeType
     */
    private $mimeType;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $content
     */
    private $content;

    /**
     * {@inheritdoc}
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * {@inheritdoc}
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * {@inheritdoc}
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function equals(Equatable $object)
    {
        return
            $object instanceof self
            and
            $this->getMimeType() === $object->getMimeType()
            and
            $this->getName() === $object->getName()
            and
            $this->getContent() === $object->getContent()
        ;
    }
}
