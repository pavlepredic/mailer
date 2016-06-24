<?php

namespace Tests\Service;

use HelloFresh\Mailer\Service\Enqueuer;
use Prophecy\Argument;
use Tests\Implementation\Common\Factory;

class EnqueuerTest extends \PHPUnit_Framework_TestCase
{
    public function testEnqueue()
    {
        $message = Factory::createMessage();
        $payload = 'payload';
        $topic = 'topic';
        $serializer = $this->prophesize('HelloFresh\Mailer\Contract\SerializerInterface');
        $serializer
            ->serialize($message)
            ->shouldBeCalled()
            ->willReturn($payload)
        ;
        $topicGenerator = $this->prophesize('HelloFresh\Mailer\Helper\TopicGenerator');
        $topicGenerator
            ->generate(Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn($topic)
        ;
        $producer = $this->prophesize('HelloFresh\Mailer\Contract\EventProducerInterface');
        $producer
            ->produce($payload, $topic)
            ->shouldBeCalled()
            ->willReturn(true);

        $enqueuer = new Enqueuer($producer->reveal(), null, $serializer->reveal(), null, $topicGenerator->reveal());
        $return = $enqueuer->enqueue($message);
        $this->assertTrue($return);
    }
}
