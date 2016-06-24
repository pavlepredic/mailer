<?php

namespace HelloFresh\Mailer\Service;

use HelloFresh\Mailer\Contract\EventConsumerInterface;
use HelloFresh\Mailer\Contract\EventProducerInterface;
use HelloFresh\Mailer\Contract\MailerInterface;
use HelloFresh\Mailer\Contract\PriorityInterface;
use HelloFresh\Mailer\Contract\SerializerInterface;
use HelloFresh\Mailer\Exception\ResponseException;
use HelloFresh\Mailer\Exception\SerializationException;
use HelloFresh\Mailer\Helper\TopicGenerator;
use HelloFresh\Mailer\Implementation\Common\JsonSerializer;
use HelloFresh\Mailer\Implementation\Common\SendAttempt;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Listener
{
    /**
     * @var EventProducerInterface $eventProducer
     */
    private $eventProducer;

    /**
     * @var EventConsumerInterface $eventConsumer
     */
    private $eventConsumer;

    /**
     * @var MailerInterface $mailer
     */
    private $mailer;

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
     * @var Sender $sender
     */
    private $sender;

    /**
     * Listener constructor.
     * @param MailerInterface $mailer
     * @param EventProducerInterface $eventProducer
     * @param EventConsumerInterface $eventConsumer
     * @param Configuration $configuration
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     * @param TopicGenerator $topicGenerator
     * @param Sender $sender
     */
    public function __construct(
        MailerInterface $mailer,
        EventProducerInterface $eventProducer,
        EventConsumerInterface $eventConsumer,
        Configuration $configuration = null,
        SerializerInterface $serializer = null,
        LoggerInterface $logger = null,
        TopicGenerator $topicGenerator = null,
        Sender $sender = null
    ) {
        $this->mailer = $mailer;
        $this->eventProducer = $eventProducer;
        $this->eventConsumer = $eventConsumer;
        $this->configuration = $configuration ? $configuration : new Configuration();
        $this->serializer = $serializer ? $serializer : new JsonSerializer();
        $this->logger = $logger ? $logger : new NullLogger();
        if (!$topicGenerator) {
            $topicGenerator = new TopicGenerator($this->configuration->topicNamespace);
        }
        $this->topicGenerator = $topicGenerator;
        if (!$sender) {
            $sender = new Sender(
                $this->eventProducer,
                $this->configuration,
                $this->serializer,
                $this->logger
            );
        }
        $this->sender = $sender;
    }

    /**
     * Listens for new messages in the queue and consumes them by providing
     * self::consume as the callback to the consumer
     * @param PriorityInterface $priority
     * @return void
     */
    public function listen(PriorityInterface $priority)
    {
        $this->logger->info(sprintf("Listening for %s messages...", $priority->toString()));
        $topic = $this->topicGenerator->generate([
            $this->configuration->topicPending,
            $priority->toString(),
        ]);
        $this->eventConsumer->consume([$this, 'consume'], $topic);
    }

    /**
     * Unserializes the provided $eventMessage into a Message
     * and sends it using the Mailer.
     * Returns true to indicate that the $eventMessage was consumed and can be removed from the queue.
     * Returns false to indicate that the $eventMessage was not consumed and should be put back onto queue.
     * This method is not a part of the public interface. It needs to be public because it is provided as
     * a callback to the EventConsumer
     * @param string $eventMessage
     * @return bool
     */
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
                    $this->eventProducer->produce(
                        $eventMessage,
                        $this->topicGenerator->generate([
                            $this->configuration->topicSent,
                        ])
                    );
                }
                $this->logger->info('Sent: ' . $eventMessage);
            } else {
                if ($this->configuration->triggerFailedEvents) {
                    $this->eventProducer->produce(
                        $eventMessage,
                        $this->topicGenerator->generate([
                            $this->configuration->topicFailed,
                        ])
                    );
                }
                $this->logger->error('Failed [recipient denied]: ' . $eventMessage);
            }

            return true; //we acknowledge the message, so it is taken out of queue

        } catch (SerializationException $se) {

            $this->eventProducer->produce(
                $eventMessage,
                $this->topicGenerator->generate([
                    $this->configuration->topicException,
                ])
            );
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
                $this->sender->enqueue($message);
            } else {
                $this->eventProducer->produce(
                    $eventMessage,
                    $this->topicGenerator->generate([
                        $this->configuration->topicException,
                    ])
                );
                $this->logger->error(sprintf("Exception [%s]: %",  $re->getMessage(), $eventMessage));
            }

            return true; //we acknowledge the message, so it is taken out of queue
        }
    }
}
