<?php

namespace HelloFresh\Mailer\Implementation\Common;

use HelloFresh\Mailer\Contract\ResponseInterface;

abstract class Response implements ResponseInterface
{
    /**
     * @var string $status
     */
    private $status;

    /**
     * @var string $error
     */
    private $error;

    /**
     * Response constructor.
     * @param string $status
     * @param string $error
     */
    public function __construct($status = null, $error = null)
    {
        $this->status = $status;
        $this->error = $error;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * {@inheritdoc}
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return in_array($this->getStatus(), $this->getSuccessStatuses());
    }

    /**
     * @return array
     */
    abstract public function getSuccessStatuses();

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('Status: %s, error: %s', $this->getStatus(), $this->getError());
    }
}
