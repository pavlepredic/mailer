<?php

namespace HelloFresh\Mailer\Contract;

use HelloFresh\Mailer\Exception\ResponseException;

interface MailerInterface
{
    /**
     * Sends message
     * @param MessageInterface $message
     * @throws ResponseException
     * @return ResponseInterface
     */
    public function send(MessageInterface $message);
}
