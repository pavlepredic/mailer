<?php

namespace Tests;

use HelloFresh\Mailer\Configuration;
use HelloFresh\Mailer\Implementation\Mandrill\Response;
use HelloFresh\Mailer\Implementation\Dummy\Queue\EventConsumer;
use HelloFresh\Mailer\Implementation\Dummy\Queue\EventProducer;
use HelloFresh\Mailer\Service;
use Prophecy\Argument;
use Tests\Implementation\Common\Factory;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testEnqueue()
    {
        $message = Factory::createMessage();
        $mailer = $this->prophesize('HelloFresh\Mailer\Contract\MailerInterface');
        $mailer
            ->send(Argument::type('HelloFresh\Mailer\Implementation\Common\Message'))
            ->shouldBeCalled()
            ->willReturn(new Response('sent'))
        ;
        $producer = new EventProducer();
        $consumer = new EventConsumer();
        $service = new Service($mailer->reveal(), $producer, $consumer, new Configuration());

        $service->enqueue($message);
        $service->listen($message->getPriority());
    }
}
