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
use HelloFresh\Mailer\Implementation\Common\SendAttempt;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Service
{
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
     * @var Configuration $configuration
     */
    private $configuration;

    /**
     * Service constructor.
     * @param MailerInterface $mailer
     * @param EventProducerInterface $eventProducer
     * @param EventConsumerInterface $eventConsumer
     * @param Configuration $configuration
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        MailerInterface $mailer,
        EventProducerInterface $eventProducer,
        EventConsumerInterface $eventConsumer,
        Configuration $configuration,
        SerializerInterface $serializer = null,
        LoggerInterface $logger = null
    ) {
        $this->mailer = $mailer;
        $this->eventProducer = $eventProducer;
        $this->eventConsumer = $eventConsumer;
        $this->configuration = $configuration;
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
        $payload = $this->serializer->serialize($message);
        $priority = $message->getPriority()->toString();
        $topic = $this->getTopic([$this->configuration->topicPending, $priority]);
        $this->logger->debug("Enqueuing message: " . $payload);
        $this->eventProducer->produce($payload, $topic);
    }

    public function listen(PriorityInterface $priority)
    {
        $this->logger->info(sprintf("Listening for %s messages...", $priority->toString()));
        $topic = $this->getTopic([$this->configuration->topicPending, $priority->toString()]);
        $this->eventConsumer->consume([$this, 'consume'], $topic);
    }

    public function consume($eventMessage)
    {
        try {
            $message = $this->serializer->unserialize($eventMessage);
            $this->logger->debug('Got message: ' . $eventMessage);
            $lastAttempt = $message->getLastSendAttempt();
            if ($lastAttempt) {
                $this->logger->debug(sprintf(
                    "I have already tried to send this message before. Last attempt was on %s",
                    $lastAttempt->getTimestamp()->format('c')
                ));

                $elapsedWaitTime = $lastAttempt->getElapsedTime();
                $remainingWaitTime = $this->configuration->resendDelay - $elapsedWaitTime;

                if ($elapsedWaitTime < $this->configuration->resendDelay) {
                    $this->logger->debug(sprintf(
                        "I will not yet attempt to re-send it (waiting for %s more seconds to elapse)",
                        $remainingWaitTime
                    ));
                    return false; //we don't acknowledge the message, so it remains in queue
                }
            }

            $this->logger->debug('Sending...');
            $response = $this->mailer->send($message);
            if ($response->isSuccessful()) {
                if ($this->configuration->triggerSentEvents) {
                    $this->eventProducer->produce($eventMessage, $this->getTopic([$this->configuration->topicSent]));
                }
                $this->logger->info('Sent: ' . $eventMessage);
            } else {
                if ($this->configuration->triggerFailedEvents) {
                    $this->eventProducer->produce($eventMessage, $this->getTopic([$this->configuration->topicFailed]));
                }
                $this->logger->error('Failed [recipient denied]: ' . $eventMessage);
            }

            return true; //we acknowledge the message, so it is taken out of queue

        } catch (SerializationException $se) {

            $this->eventProducer->produce($eventMessage, $this->getTopic([$this->configuration->topicException]));
            $this->logger->error('Exception [serialization exception]: ' . $eventMessage);
            return true; //we acknowledge the message, so it is taken out of queue

        } catch (ResponseException $re) {

            $this->logger->debug(sprintf('ResponseException: %s', $re->getMessage()));
            $sendAttempt = new SendAttempt();
            $sendAttempt->setStatus(SendAttempt::STATUS_ERROR);
            $sendAttempt->setError($re->getMessage());
            $message->addSendAttempt($sendAttempt);
            if ($message->countSendAttempts() < $this->configuration->sendAttempts) {
                $this->logger->debug('Putting the message back onto queue');
                $this->enqueue($message);
            } else {
                $this->eventProducer->produce($eventMessage, $this->getTopic([$this->configuration->topicException]));
                $this->logger->error(sprintf("Exception [%s]: %",  $re->getMessage(), $eventMessage));
            }

            return true; //we acknowledge the message, so it is taken out of queue
        }
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

        if ($this->configuration->topicNamespace) {
            array_unshift($topicElements, $this->configuration->topicNamespace);
        }

        return join('.', $topicElements);
    }
}
