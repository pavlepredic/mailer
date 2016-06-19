<?php

namespace HelloFresh\Mailer\Implementation\Common\Status;

use HelloFresh\Mailer\Contract\StatusInterface;

abstract class AbstractStatus implements StatusInterface
{
    /**
     * @var string $message
     */
    private $message;

    /**
     * AbstractStatus constructor.
     * @param string $message
     */
    public function __construct($message = null)
    {
        $this->message = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
}
