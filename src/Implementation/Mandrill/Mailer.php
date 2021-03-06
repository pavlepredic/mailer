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

            return $this->parseResponse($response, $message);
        } catch (\Mandrill_Error $e) {
            throw new ResponseException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param array $response
     * @param MessageInterface $message
     * @return Response
     * @throws ResponseException
     */
    protected function parseResponse($response, MessageInterface $message)
    {
        foreach ($response as $recipientStatus) {
            if (!isset($recipientStatus['email']) or !isset($recipientStatus['status'])) {
                continue;
            }

            if ($recipientStatus['email'] === $message->getRecipient()->getEmail()) {
                return new Response($recipientStatus['status'], @$recipientStatus['reject_reason']);
            }
        }

        throw new ResponseException("Invalid API response: missing recipient email in response");
    }
}
