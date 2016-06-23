<?php

namespace HelloFresh\Mailer\Implementation\Common;

use HelloFresh\Mailer\Contract\RecipientInterface;
use HelloFresh\Mailer\Exception\InvalidArgumentException;

class Recipient extends Participant implements RecipientInterface
{
    /**
     * @var string $type
     */
    private $type;

    /**
     * Recipient constructor.
     * @param string $type
     */
    public function __construct($type = null)
    {
        if (!$type) {
            $type = self::TYPE_TO;
        }
        $this->setType($type);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        if (!in_array($type, self::getValidTypes())) {
            throw new InvalidArgumentException("Invalid recipient type: $type");
        }

        $this->type = $type;

        return $this;
    }

    /**
     * Array of valid types (to, cc and bcc)
     * @return array
     */
    public static function getValidTypes()
    {
        return [
            self::TYPE_TO,
            self::TYPE_CC,
            self::TYPE_BCC,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $array = parent::toArray();
        $array[] = $this->getType();
        return $array;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $array)
    {
        $recipient = parent::fromArray($array);
        $recipient->setType(array_pop($array));
        return $recipient;
    }

    /**
     * {@inheritdoc}
     */
    public static function getArrayDefinition()
    {
        $definition = parent::getArrayDefinition();
        $definition[] = 'string';
        return $definition;
    }
}
