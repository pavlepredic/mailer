<?php

namespace Tests;

use HelloFresh\Mailer\Implementation\Common\JsonSerializer;
use HelloFresh\Mailer\Service;
use Tests\Implementation\Common\Factory;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testEnqueue()
    {
        $message = Factory::createMessage();
        $serializer = new JsonSerializer();
        $mailer = $this->prophesize('HelloFresh\Mailer\Contract\MailerInterface');
        $producer = $this->prophesize('HelloFresh\Reagieren\ProducerInterface');
        $producer
            ->produce($message->getPriority()->toString(), $serializer->serialize($message))
            ->shouldBeCalled()
        ;
        $consumer = $this->prophesize('HelloFresh\Reagieren\ConsumerInterface');
        $service = new Service($mailer->reveal(), $producer->reveal(), $consumer->reveal());

        $service->setEventProducer($producer->reveal());
        $service->enqueue($message);
    }
}
