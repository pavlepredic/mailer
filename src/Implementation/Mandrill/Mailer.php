<?php

namespace HelloFresh\Mailer\Implementation\Mandrill;

use HelloFresh\Mailer\Contract\MailerInterface;
use HelloFresh\Mailer\Contract\MessageInterface;
use HelloFresh\Mailer\Exception\ResponseException;

class Mailer implements MailerInterface
{
    /**
     * @var \Mandrill_Messages $mandrill
     */
    private $mandrill;

    /**
     * Mailer constructor.
     * @param \Mandrill_Messages $mandrill
     */
    public function __construct(\Mandrill_Messages $mandrill)
    {
        $this->mandrill = $mandrill;
    }

    /**
     * {@inheritdoc}
     */
    public function send(MessageInterface $message)
    {
        $mandrillMessage = new MessageDecorator($message);
        try {
            if ($message->getTemplate()) {
                $response = $this->mandrill->sendTemplate(
                    $message->getTemplate(),
                    [],
                    $mandrillMessage->toArray()
                );
            } else {
                $response = $this->mandrill->send($mandrillMessage->toArray());
            }

            $response = $response[$message->getRecipient()->getEmail()];
            return new Response($response['status'], $response['reject_reason']);
        } catch (\Mandrill_Error $e) {
            throw new ResponseException("Mandrill API error", null, $e);
        }
    }
}
