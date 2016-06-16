<?php

namespace HelloFresh\Mailer\Contract;

use HelloFresh\Mailer\Exception\MailerException;

interface MailerInterface
{
    /**
     * Sends message
     * @param MessageInterface $message
     * @throws MailerException
     * @return void
     */
    public function send(MessageInterface $message);
}
