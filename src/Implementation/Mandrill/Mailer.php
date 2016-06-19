<?php

namespace HelloFresh\Mailer\Implementation\Mandrill;

use HelloFresh\Mailer\Contract\MailerInterface;
use HelloFresh\Mailer\Contract\MessageInterface;
use HelloFresh\Mailer\Exception\ResponseException;

class Mailer implements MailerInterface
{
    /**
     * @var \Mandrill_Messages $sender
     */
    private $sender;

    /**
     * Mailer constructor.
     * @param \Mandrill_Messages $sender
     */
    public function __construct(\Mandrill_Messages $sender)
    {
        $this->sender = $sender;;
    }

    /**
     * {@inheritdoc}
     */
    public function send(MessageInterface $message)
    {
        $mandrillMessage = new MessageDecorator($message);
        try {
            if ($message->getTemplate()) {
                $response = $this->getSender()->sendTemplate(
                    $message->getTemplate(),
                    $mandrillMessage->getMergeVariables(),
                    $mandrillMessage->toArray()
                );
            } else {
                $response = $this->getSender()->send($mandrillMessage->toArray());
            }

            $response = $response[$message->getRecipient()->getEmail()];
            return new Response($response['status'], $response['reject_reason']);
        } catch (\Mandrill_Error $e) {
            throw new ResponseException("Mandrill API error", null, $e);
        }
    }

    /**
     * @return \Mandrill_Messages
     */
    protected function getSender()
    {
        return $this->sender;
    }
}
