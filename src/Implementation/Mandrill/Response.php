<?php

namespace HelloFresh\Mailer\Implementation\Mandrill;

class Response extends \HelloFresh\Mailer\Implementation\Common\Response
{
    public function getSuccessStatuses()
    {
        return [
            'sent',
            'queued',
        ];
    }
}
