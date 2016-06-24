<?php

namespace HelloFresh\Mailer\Implementation\Common;

use HelloFresh\Mailer\Contract\AttachmentInterface;

class Attachment implements AttachmentInterface
{
    use ArrayValidatorTrait;

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
        if ($this->content === null) {
            return null;
        }
        return base64_decode($this->content);
    }

    /**
     * {@inheritdoc}
     */
    public function setContent($content)
    {
        $this->content = base64_encode($content);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            $this->getMimeType(),
            $this->getName(),
            $this->getContent(),
        ];
    }

    /**
     * {@inheritdoc}
     * @return Attachment
     */
    public static function fromArray(array $array)
    {
        static::validateArray(static::getArrayDefinition(), $array);

        $attachment = new static;
        $attachment->setMimeType($array[0]);
        $attachment->setName($array[1]);
        $attachment->setContent($array[2]);
        return $attachment;
    }

    /**
     * {@inheritdoc}
     */
    public static function getArrayDefinition()
    {
        return [
            'string',
            'string',
            'string',
        ];
    }
}
