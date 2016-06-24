<?php

namespace Tests\Service;

use HelloFresh\Mailer\Contract\ResponseInterface;
use HelloFresh\Mailer\Exception\MailerException;
use HelloFresh\Mailer\Exception\ResponseException;
use HelloFresh\Mailer\Exception\SerializationException;
use HelloFresh\Mailer\Implementation\Common\Priority\NormalPriority;
use HelloFresh\Mailer\Implementation\Dummy\Response\Error;
use HelloFresh\Mailer\Implementation\Dummy\Response\Success;
use HelloFresh\Mailer\Service\Configuration;
use HelloFresh\Mailer\Service\Listener;
use HelloFresh\Mailer\Service\Sender;
use Prophecy\Argument;
use Tests\Implementation\Common\Factory;

class ListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testListen()
    {
        $topic = 'topic';

        $mailer = $this->prophesize('HelloFresh\Mailer\Contract\MailerInterface');
        $serializer = $this->prophesize('HelloFresh\Mailer\Contract\SerializerInterface');

        $topicGenerator = $this->prophesize('HelloFresh\Mailer\Helper\TopicGenerator');
        $topicGenerator
            ->generate(Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn($topic)
        ;

        $producer = $this->prophesize('HelloFresh\Mailer\Contract\EventProducerInterface');

        $consumer = $this->prophesize('HelloFresh\Mailer\Contract\EventConsumerInterface');
        $consumer
            ->consume(Argument::type('callable'), $topic)
            ->shouldBeCalled()
        ;

        $listener = new Listener(
            $mailer->reveal(),
            $producer->reveal(),
            $consumer->reveal(),
            null,
            $serializer->reveal(),
            null,
            $topicGenerator->reveal()
        );

        $listener->listen(new NormalPriority());
    }

    /**
     * @param ResponseInterface $response
     * @param Configuration $configuration
     * @dataProvider consumeProvider
     */
    public function testConsume(ResponseInterface $response, Configuration $configuration)
    {
        $message = Factory::createMessage();
        $payload = 'payload';
        $topicSent = 'sent';
        $topicFailed = 'sent';

        $mailer = $this->prophesize('HelloFresh\Mailer\Contract\MailerInterface');
        $mailer
            ->send($message)
            ->shouldBeCalled()
            ->willReturn($response)
        ;

        $serializer = $this->prophesize('HelloFresh\Mailer\Contract\SerializerInterface');
        $serializer
            ->unserialize($payload)
            ->shouldBeCalled()
            ->willReturn($message)
        ;

        $topicGenerator = $this->prophesize('HelloFresh\Mailer\Helper\TopicGenerator');
        $producer = $this->prophesize('HelloFresh\Mailer\Contract\EventProducerInterface');
        if ($response->isSuccessful() and $configuration->triggerSentEvents) {
            $producer
                ->produce($payload, $topicSent)
                ->shouldBeCalled()
                ->willReturn(true)
            ;

            $topicGenerator
                ->generate([$configuration->topicSent])
                ->shouldBeCalled()
                ->willReturn($topicSent)
            ;
        }

        if (!$response->isSuccessful() and $configuration->triggerFailedEvents) {
            $producer
                ->produce($payload, $topicFailed)
                ->shouldBeCalled()
                ->willReturn(true)
            ;

            $topicGenerator
                ->generate([$configuration->topicFailed])
                ->shouldBeCalled()
                ->willReturn($topicFailed)
            ;
        }

        $consumer = $this->prophesize('HelloFresh\Mailer\Contract\EventConsumerInterface');

        $listener = new Listener(
            $mailer->reveal(),
            $producer->reveal(),
            $consumer->reveal(),
            $configuration,
            $serializer->reveal(),
            null,
            $topicGenerator->reveal()
        );

        $listener->consume($payload);
    }

    /**
     * @param MailerException $exception
     * @param Configuration $configuration
     * @dataProvider consumeWithExceptionProvider
     */
    public function testConsumeWithException(MailerException $exception, Configuration $configuration)
    {
        $message = Factory::createMessage();
        $payload = 'payload';
        $topicSent = 'sent';
        $topicFailed = 'failed';
        $topicException = 'exception';

        $topicGenerator = $this->prophesize('HelloFresh\Mailer\Helper\TopicGenerator');

        $sender = $this->prophesize('HelloFresh\Mailer\Service\Sender');

        $serializer = $this->prophesize('HelloFresh\Mailer\Contract\SerializerInterface');

        $mailer = $this->prophesize('HelloFresh\Mailer\Contract\MailerInterface');

        if ($exception instanceof SerializationException) {
            $serializer
                ->unserialize($payload)
                ->shouldBeCalled()
                ->willThrow($exception);
            ;
            $topicGenerator
                ->generate([$configuration->topicException])
                ->shouldBeCalled()
                ->willReturn($topicException)
            ;
        } elseif ($exception instanceof ResponseException) {
            $serializer
                ->unserialize($payload)
                ->shouldBeCalled()
                ->willReturn($message);
            ;

            $mailer
                ->send($message)
                ->shouldBeCalled()
                ->willThrow($exception)
            ;

            $sender
                ->enqueue($message)
                ->shouldBeCalled()
                ->willReturn(true)
            ;
        }

        $producer = $this->prophesize('HelloFresh\Mailer\Contract\EventProducerInterface');
        $consumer = $this->prophesize('HelloFresh\Mailer\Contract\EventConsumerInterface');

        $listener = new Listener(
            $mailer->reveal(),
            $producer->reveal(),
            $consumer->reveal(),
            $configuration,
            $serializer->reveal(),
            null,
            $topicGenerator->reveal(),
            $sender->reveal()
        );

        $listener->consume($payload);
    }

    public function consumeProvider()
    {
        $c1 = new Configuration();
        $c2 = new Configuration();
        $c2->triggerSentEvents = true;
        $c3 = new Configuration();
        $c3->triggerFailedEvents = true;
        $success = new Success();
        $error = new Error();
        return [
            [$success, $c1],
            [$success, $c2],
            [$success, $c3],
            [$error, $c1],
            [$error, $c2],
            [$error, $c3],
        ];
    }

    public function consumeWithExceptionProvider()
    {
        $c1 = new Configuration();
        $c2 = new Configuration();
        $c2->triggerFailedEvents = true;
        $e1 = new SerializationException();
        $e2 = new ResponseException();
        return [
            [$e1, $c1],
            [$e1, $c2],
            [$e2, $c1],
            [$e2, $c2],
        ];
    }
}
