<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Logger extends \Psr\Log\AbstractLogger {
    public function log($level, $message, array $context = array())
    {
        echo $message . "\n";
    }
}

$response = new \HelloFresh\Mailer\Implementation\Common\Response\Error();
$mailer = new \HelloFresh\Mailer\Implementation\Common\NoOpMailer($response, new \HelloFresh\Mailer\Exception\ResponseException());
$producer = new \HelloFresh\Mailer\Implementation\Queue\EventProducer();
$consumer = new \HelloFresh\Mailer\Implementation\Queue\EventConsumer();
$config = new \HelloFresh\Mailer\Configuration();
$service = new \HelloFresh\Mailer\Service($mailer, $producer, $consumer, $config, null, new Logger());

$message = \Tests\Implementation\Common\Factory::createMessage();

$service->enqueue($message);


$service->listen(new \HelloFresh\Mailer\Implementation\Common\Priority\HighPriority());
