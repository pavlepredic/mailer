<?php

namespace HelloFresh\Mailer;

use HelloFresh\Mailer\Contract\MessageInterface;
use HelloFresh\Mailer\Contract\SerializerInterface;
use HelloFresh\Reagieren\ConsumerInterface;
use HelloFresh\Reagieren\ProducerInterface;

class Service
{
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
}
