<?php

namespace HelloFresh\Mailer;

use HelloFresh\Mailer\Contract\EventConsumerInterface;
use HelloFresh\Mailer\Contract\EventProducerInterface;
use HelloFresh\Mailer\Contract\MailerInterface;
use HelloFresh\Mailer\Contract\MessageInterface;
use HelloFresh\Mailer\Contract\PriorityInterface;
use HelloFresh\Mailer\Contract\SerializerInterface;
use HelloFresh\Mailer\Exception\ResponseException;
use HelloFresh\Mailer\Exception\SerializationException;
use HelloFresh\Mailer\Implementation\Common\JsonSerializer;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Service
{
    const TOPIC_PENDING = 'pending';
    const TOPIC_SENT = 'sent';
    const TOPIC_FAILED = 'failed';
    const TOPIC_EXCEPTION = 'exception';

    /**
     * @var MailerInterface $mailer
     */
    private $mailer;

    /**
     * @var EventProducerInterface $eventProducer
     */
    private $eventProducer;

    /**
     * @var EventConsumerInterface $eventConsumer
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
     * @var string $topicNamespace
     */
    private $topicNamespace;

    /**
     * Service constructor.
     * @param MailerInterface $mailer
     * @param EventProducerInterface $eventProducer
     * @param EventConsumerInterface $eventConsumer
     * @param SerializerInterface $serializer
     * @param string $topicNamespace
     * @param LoggerInterface $logger
     */
    public function __construct(
        MailerInterface $mailer,
        EventProducerInterface $eventProducer,
        EventConsumerInterface $eventConsumer,
        SerializerInterface $serializer = null,
        $topicNamespace = null,
        LoggerInterface $logger = null
    ) {
        $this->mailer = $mailer;
        $this->eventProducer = $eventProducer;
        $this->eventConsumer = $eventConsumer;
        $this->serializer = $serializer ? $serializer : new JsonSerializer();
        $this->topicNamespace = $topicNamespace;
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
        $topic = $this->getTopic([self::TOPIC_PENDING, $priority]);
        $this->getEventProducer()->produce($payload, $topic);
    }

    public function listen(PriorityInterface $priority)
    {
        $topic = $this->getTopic([self::TOPIC_PENDING, $priority->toString()]);
        $this->getEventConsumer()->consume([$this, 'consume'], $topic);
    }

    public function consume($eventMessage)
    {
        try {
            $message = $this->getSerializer()->unserialize($eventMessage);
            $response = $this->getMailer()->send($message);
            if ($response->isSuccessful()) {
                $this->getEventProducer()->produce($eventMessage, $this->getTopic([self::TOPIC_SENT]));
            } else {
                $this->getEventProducer()->produce($eventMessage, $this->getTopic([self::TOPIC_FAILED]));
            }
        } catch (SerializationException $se) {
            $this->getEventProducer()->produce($eventMessage, $this->getTopic([self::TOPIC_EXCEPTION]));
        } catch (ResponseException $re) {
            //TODO re-schedule email
        }

        return true;
    }

    /**
     * @return EventProducerInterface
     */
    public function getEventProducer()
    {
        return $this->eventProducer;
    }

    /**
     * @param EventProducerInterface $eventProducer
     * @return Service
     */
    public function setEventProducer(EventProducerInterface $eventProducer)
    {
        $this->eventProducer = $eventProducer;
        return $this;
    }

    /**
     * @return EventConsumerInterface
     */
    public function getEventConsumer()
    {
        return $this->eventConsumer;
    }

    /**
     * @param EventConsumerInterface $eventConsumer
     * @return Service
     */
    public function setEventConsumer(EventConsumerInterface $eventConsumer)
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

    /**
     * @param array|string $topicElements
     * @return string
     */
    public function getTopic($topicElements)
    {
        if (!is_array($topicElements)) {
            $topicElements = [$topicElements];
        }

        if ($this->topicNamespace) {
            array_unshift($topicElements, $this->topicNamespace);
        }

        return join('.', $topicElements);
    }
}
