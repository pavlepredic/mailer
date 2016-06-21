<?php

namespace HelloFresh\Mailer\Implementation\Dummy;

use HelloFresh\Mailer\Contract\MailerInterface;
use HelloFresh\Mailer\Contract\MessageInterface;
use HelloFresh\Mailer\Contract\ResponseInterface;
use HelloFresh\Mailer\Exception\ResponseException;

/**
 * A no-op mailer that returns the specified response
 * or throws the specified exception
 */
class Mailer implements MailerInterface
{
    /**
     * @var ResponseInterface $response
     */
    private $response;

    /**
     * @var ResponseException $exception
     */
    private $exception;

    /**
     * @param ResponseInterface $response
     * @param ResponseException $exception
     */
    public function __construct(ResponseInterface $response, ResponseException $exception = null)
    {
        $this->response = $response;
        $this->exception = $exception;
    }

    /**
     * {@inheritdoc}
     */
    public function send(MessageInterface $message)
    {
        if ($this->exception) {
            throw $this->exception;
        }
        return $this->response;
    }
}
