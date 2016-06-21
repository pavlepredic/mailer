<?php

namespace HelloFresh\Mailer\Implementation\Dummy\Response;

use HelloFresh\Mailer\Implementation\Common\Response;

class Success extends Response
{
    /**
     * {@inheritdoc}
     */
    public function getSuccessStatuses()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return true;
    }
}
