<?php

namespace HelloFresh\Mailer\Implementation\Common;

use HelloFresh\Mailer\Contract\SendAttemptInterface;
use HelloFresh\Mailer\Exception\InvalidArgumentException;

class SendAttempt implements SendAttemptInterface
{
    /**
     * @var \DateTime $timestamp
     */
    private $timestamp;

    /**
     * @var string $status
     */
    private $status;

    /**
     * @var string $error
     */
    private $error;

    /**
     * SendAttempt constructor.
     * @param \DateTime $timestamp
     */
    public function __construct(\DateTime $timestamp = null)
    {
        if (!$timestamp) {
            $timestamp = new \DateTime();
        }

        $this->timestamp = $timestamp;
    }


    /**
     * {@inheritdoc}
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * {@inheritdoc}
     */
    public function setTimestamp(\DateTime $timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
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
        if (!in_array($status, self::getValidStatuses())) {
            throw new InvalidArgumentException("Invalid status: $status");
        }
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
    public function getElapsedTime(\DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }

        return $now->getTimestamp() - $this->getTimestamp()->getTimestamp();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            $this->getTimestamp()->format('c'),
            $this->getStatus(),
            $this->getError(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $array)
    {
        //TODO validate array
        $attempt = new static;
        $attempt->setTimestamp(new \DateTime($array[0]));
        $attempt->setStatus($array[1]);
        $attempt->setError($array[2]);
        return $attempt;
    }

    /**
     * List of valid statuses
     * @return array
     */
    public static function getValidStatuses()
    {
        return [
            self::STATUS_SENT,
            self::STATUS_FAILED,
            self::STATUS_ERROR,
        ];
    }
}
