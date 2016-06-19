<?php

namespace HelloFresh\Mailer\Contract;

interface ResponseInterface
{
    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param string $status
     * @return ResponseInterface
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getError();

    /**
     * @param string $error
     * @return ResponseInterface
     */
    public function setError($error);

    /**
     * Indicates whether or not the message was successfully sent
     * @return boolean
     */
    public function isSuccessful();
}
