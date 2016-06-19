<?php

namespace HelloFresh\Mailer\Implementation\Common;

use HelloFresh\Mailer\Contract\RecipientInterface;
use HelloFresh\Mailer\Contract\StatusInterface;
use HelloFresh\Mailer\Exception\InvalidArgumentException;
use HelloFresh\Mailer\Implementation\Common\Status\Pending;

class Recipient extends Participant implements RecipientInterface
{
    /**
     * @var string $type
     */
    private $type;

    /**
     * @var StatusInterface $status
     */
    private $status;

    /**
     * Recipient constructor.
     * @param string $type
     * @param StatusInterface $status
     */
    public function __construct($type = null, StatusInterface $status = null)
    {
        if (!$type) {
            $type = self::TYPE_TO;
        }

        if (!$status) {
            $status = new Pending();
        }
        $this->setType($type);
        $this->setStatus($status);
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
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus(StatusInterface $status)
    {
        $this->status = $status;

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
}
