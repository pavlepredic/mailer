<?php

namespace Tests\Service;

use HelloFresh\Mailer\Service\Sender;
use Prophecy\Argument;
use Tests\Implementation\Common\Factory;

class SenderTest extends \PHPUnit_Framework_TestCase
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

        $sender = new Sender($producer->reveal(), null, $serializer->reveal(), null, $topicGenerator->reveal());
        $return = $sender->enqueue($message);
        $this->assertTrue($return);
    }
}
