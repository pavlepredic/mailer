<?php

namespace HelloFresh\Mailer\Service;

use HelloFresh\Mailer\Contract\EventProducerInterface;
use HelloFresh\Mailer\Contract\MessageInterface;
use HelloFresh\Mailer\Contract\SerializerInterface;
use HelloFresh\Mailer\Helper\TopicGenerator;
use HelloFresh\Mailer\Implementation\Common\JsonSerializer;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Sender
{
    /**
     * @var EventProducerInterface $eventProducer
     */
    private $eventProducer;

    /**
     * @var Configuration $configuration
     */
    private $configuration;

    /**
     * @var SerializerInterface $serializer
     */
    private $serializer;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * @var TopicGenerator $topic
     */
    private $topicGenerator;

    /**
     * Sender constructor.
     * @param EventProducerInterface $eventProducer
     * @param Configuration $configuration
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     * @param TopicGenerator $topicGenerator
     */
    public function __construct(
        EventProducerInterface $eventProducer,
        Configuration $configuration = null,
        SerializerInterface $serializer = null,
        LoggerInterface $logger = null,
        TopicGenerator $topicGenerator = null
    ) {
        $this->eventProducer = $eventProducer;
        $this->configuration = $configuration ? $configuration : new Configuration();
        $this->serializer = $serializer ? $serializer : new JsonSerializer();
        $this->logger = $logger ? $logger : new NullLogger();
        if (!$topicGenerator) {
            $topicGenerator = new TopicGenerator($this->configuration->topicNamespace);
        }
        $this->topicGenerator = $topicGenerator;
    }


    /**
     * Adds message to queue
     * (sends an event with the message as the payload)
     * @param MessageInterface $message
     * @return boolean
     */
    public function enqueue(MessageInterface $message)
    {
        $payload = $this->serializer->serialize($message);
        $priority = $message->getPriority()->toString();
        $topic = $this->topicGenerator->generate([
            $this->configuration->topicPending,
            $priority
        ]);
        $this->logger->debug("Enqueuing message: " . $payload);
        return $this->eventProducer->produce($payload, $topic);
    }
}
