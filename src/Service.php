<?php

namespace HelloFresh\Mailer;

use HelloFresh\Mailer\Contract\MailerInterface;
use HelloFresh\Mailer\Contract\MessageInterface;
use HelloFresh\Mailer\Contract\SerializerInterface;
use HelloFresh\Mailer\Implementation\Common\JsonSerializer;
use HelloFresh\Reagieren\ConsumerInterface;
use HelloFresh\Reagieren\ProducerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Service
{
    /**
     * @var MailerInterface $mailer
     */
    private $mailer;

    /**
     * @var ProducerInterface $eventProducer
     */
    private $eventProducer;

    /**
     * @var ConsumerInterface $eventConsumer
     */
    private $eventConsumer;

    /**
     * @var SerializerInterface $serializer
     */
    private $serializer;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * Service constructor.
     * @param MailerInterface $mailer
     * @param ProducerInterface $eventProducer
     * @param ConsumerInterface $eventConsumer
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        MailerInterface $mailer,
        ProducerInterface $eventProducer,
        ConsumerInterface $eventConsumer,
        SerializerInterface $serializer = null,
        LoggerInterface $logger = null
    ) {
        $this->mailer = $mailer;
        $this->eventProducer = $eventProducer;
        $this->eventConsumer = $eventConsumer;
        $this->serializer = $serializer ? $serializer : new JsonSerializer();
        $this->logger = $logger ? $logger : new NullLogger();
    }


    /**
     * Adds message to queue
     * (sends an event with the message as the payload)
     * @param MessageInterface $message
     */
    public function enqueue(MessageInterface $message)
    {
        $payload = $this->getSerializer()->serialize($message);
        $priority = $message->getPriority()->toString();
        $this->getEventProducer()->produce($priority, $payload);
    }

    /**
     * @return ProducerInterface
     */
    public function getEventProducer()
    {
        return $this->eventProducer;
    }

    /**
     * @param ProducerInterface $eventProducer
     * @return Service
     */
    public function setEventProducer(ProducerInterface $eventProducer)
    {
        $this->eventProducer = $eventProducer;
        return $this;
    }

    /**
     * @return ConsumerInterface
     */
    public function getEventConsumer()
    {
        return $this->eventConsumer;
    }

    /**
     * @param ConsumerInterface $eventConsumer
     * @return Service
     */
    public function setEventConsumer(ConsumerInterface $eventConsumer)
    {
        $this->eventConsumer = $eventConsumer;
        return $this;
    }

    /**
     * @return MailerInterface
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * @param MailerInterface $mailer
     * @return Service
     */
    public function setMailer(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        return $this;
    }

    /**
     * @return SerializerInterface
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @param SerializerInterface $serializer
     * @return Service
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return Service
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }
}
