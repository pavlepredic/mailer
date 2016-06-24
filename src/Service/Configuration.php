<?php

namespace HelloFresh\Mailer\Service;

class Configuration
{
    /**
     * @var string $topicNamespace
     */
    public $topicNamespace;

    /**
     * @var string $topicPending
     */
    public $topicPending = 'pending';

    /**
     * @var string $topicSent
     */
    public $topicSent = 'sent';

    /**
     * @var string $topicSent
     */
    public $topicFailed = 'failed';

    /**
     * @var string $topicSent
     */
    public $topicException = 'exception';

    /**
     * @var int $sendAttempts
     */
    public $sendAttempts = 3;

    /**
     * @var int $resendDelay
     */
    public $resendDelay = 3600;

    /**
     * @var bool $triggerSentEvents
     */
    public $triggerSentEvents = false;

    /**
     * @var bool $triggerFailedEvents
     */
    public $triggerFailedEvents = false;
}
