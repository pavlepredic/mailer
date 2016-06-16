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
        $service = new Service();
        $serializer = new JsonSerializer();
        $service->setSerializer($serializer);
        $producer = $this->prophesize('HelloFresh\Reagieren\ProducerInterface');
        $producer
            ->produce($message->getPriority()->toString(), $serializer->serialize($message))
            ->shouldBeCalled()
        ;
        $service->setEventProducer($producer->reveal());
        $service->enqueue($message);
    }
}
