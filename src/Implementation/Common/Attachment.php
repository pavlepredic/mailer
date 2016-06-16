<?php

namespace HelloFresh\Mailer\Implementation\Common;

use HelloFresh\Mailer\Contract\AttachmentInterface;
use HelloFresh\Mailer\Contract\EquatableInterface;

class Attachment implements AttachmentInterface
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
    public function equals(EquatableInterface $object)
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
