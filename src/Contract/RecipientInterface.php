<?php

namespace HelloFresh\Mailer\Contract;

use HelloFresh\Mailer\Exception\InvalidArgumentException;

interface RecipientInterface extends ParticipantInterface
{
    /**
     * Valid type constants
     */
    const TYPE_TO = 'to';
    const TYPE_CC = 'cc';
    const TYPE_BCC = 'bcc';

    /**
     * By default, implementations should return
     * self::TYPE_TO
     * @return string
     */
    public function getType();

    /**
     * @param string $type - one of:
     *  - self::TYPE_TO
     *  - self::TYPE_CC
     *  - self::TYPE_BCC
     * @return RecipientInterface
     * @throws InvalidArgumentException
     */
    public function setType($type);
}
