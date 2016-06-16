<?php

namespace HelloFresh\Mailer\Contract;

use HelloFresh\Mailer\Exception\MailerException;

interface Mailer
{
    /**
     * @param Message $message
     * @throws MailerException
     * @return void
     */
    public function send(Message $message);
}
