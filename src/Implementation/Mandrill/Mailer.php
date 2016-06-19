<?php

namespace HelloFresh\Mailer\Implementation\Mandrill;

use HelloFresh\Mailer\Contract\MailerInterface;
use HelloFresh\Mailer\Contract\MessageInterface;
use HelloFresh\Mailer\Exception\MailerException;
use HelloFresh\Mailer\Implementation\Common\Status\Rejected;
use HelloFresh\Mailer\Implementation\Common\Status\Sent;

class Mailer implements MailerInterface
{
    /**
     * @var string $apiKey
     */
    private $apiKey;

    /**
     * @var \Mandrill $mandrill
     */
    private $mandrill;

    /**
     * @var \Mandrill_Messages $sender
     */
    private $sender;

    /**
     * Mailer constructor.
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * {@inheritdoc}
     */
    public function send(MessageInterface $message)
    {
        $mandrillMessage = new MessageDecorator($message);
        try {
            $response = $this->getSender()->send($mandrillMessage->toArray());
            $this->parseResponse($response, $message);
        } catch (\Mandrill_Error $e) {
            throw new MailerException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Parses response from Mandrill and sets the ResponseStatus
     * for each Recipient of the Message
     * @param array $response
     * @param MessageInterface $message
     * @return void
     */
    private function parseResponse(array $response, MessageInterface $message)
    {
        foreach ($response as $item) {
            $recipient = $message->getRecipientByEmail($item['email']);
            if ($recipient) {
                switch ($item['status']) {
                    case 'sent' :
                    case 'queued' :
                    case 'scheduled' :
                        $recipient->setStatus(new Sent());
                        break;
                    case 'rejected' :
                    case 'invalid' :
                        $recipient->setStatus(new Rejected($item['reject_reason']));
                        break;
                }
            }
        }
    }

    /**
     * @return \Mandrill
     */
    protected function getMandrill()
    {
        if (!$this->mandrill) {
            $this->mandrill = $this->createMandrill();
        }

        return $this->mandrill;
    }

    /**
     * @return \Mandrill
     */
    protected function createMandrill()
    {
        return new \Mandrill($this->apiKey);
    }

    /**
     * @return \Mandrill_Messages
     */
    protected function getSender()
    {
        if (!$this->sender) {
            $this->sender = $this->createSender();
        }

        return $this->sender;
    }

    /**
     * @return \Mandrill_Messages
     */
    protected function createSender()
    {
        return new \Mandrill_Messages($this->getMandrill());
    }
}
