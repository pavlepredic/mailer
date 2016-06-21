<?php

namespace HelloFresh\Mailer\Contract;

interface SendAttemptInterface extends ArrayableInterface
{
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';
    const STATUS_ERROR = 'error';

    /**
     * @return \DateTime
     */
    public function getTimestamp();

    /**
     * @param \DateTime $timestamp
     * @return SendAttemptInterface
     */
    public function setTimestamp(\DateTime $timestamp);

    /**
     * @param \DateTime $now
     * @return int
     */
    public function getElapsedTime(\DateTime $now = null);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param string $status
     * @return SendAttemptInterface
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getError();

    /**
     * @param string
     * @return SendAttemptInterface
     */
    public function setError($error);
}
