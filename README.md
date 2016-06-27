# Mailer

# Description
Mailer is an event-based mailing system. It uses message queues to publish and consume email messages. It is not bound to any particular message queue implementation, but currently provides support only for RabbitMQ (by using the hellofresh/rabbit-php package).

# Main components

* **Enqueuer**: adds email messages to the queue using the `enqueue(MessageInterface)` method
* **Listener**: consumes messages from the queue and sends them using the `MailerInterface` implementation
* **Mailer**: implementation of `MailerInterface` in charge of actually sending the email messages using the `send(MessageInterface)` method

# Usage
### Overview
To use the mailer you need to do two things: 1) set up a daemon script that will consume messages from the queue and 2) add messages to the queue. Each message has a priority setting (one of: `normal_priority`, `low_priority` or `high_priority`). For each priority level, you need to create at least one listener (see below).

### Adding messages to queue

**1. Create a message:**

```php
$message = new \HelloFresh\Mailer\Implementation\Common\Message();
$message->setSender(new \HelloFresh\Mailer\Implementation\Common\Sender('no-reply@example.org', 'Sender'));
$message->setTemplate('mail_template');
$message->setSubject('Subject');
$message->setRecipient(new \HelloFresh\Mailer\Implementation\Common\Recipient('pavle.predic@example.org', 'Pavle Predic'));
$message->addVariable(new \HelloFresh\Mailer\Implementation\Common\Variable('user_name', 'Pavle Predic'));
```

**2. Instantiate an implementation of EventProducerInterface**

```php
/** @var \HelloFresh\RabbitMQ\Exchange\ExchangeInterface $connection */
$exchange = ...
$producer = new \HelloFresh\Mailer\Implementation\RabbitPhp\EventProducer($exchange);
```

**3. Create the Enqueuer and enqueue the message**

```php
$enqueuer = new \HelloFresh\Mailer\Service\Enqueuer($producer);
$enqueuer->enqueue($message);
```

### Consuming messages from the queue

**1. Create an implementation of MailerInterface (here we use Mandrill Mailer)**

```php
$madrillKey = ...; //provide your Mandrill API key
$mandrill = new Mandrill($madrillKey);
$mandrillMessages = new Mandrill_Messages($mandrill);
$mailer = new \HelloFresh\Mailer\Implementation\Mandrill\Mailer($mandrillMessages);
```

**2. Instantiate implementations of EventProducerInterface and EventConsumerInterface**

```php
/** @var \HelloFresh\RabbitMQ\Connection $connection */
$connection = ...;
/** @var \HelloFresh\RabbitMQ\Exchange\ExchangeInterface $connection */
$exchange = ...
$producer = new \HelloFresh\Mailer\Implementation\RabbitPhp\EventProducer($exchange);
$consumer = new \HelloFresh\Mailer\Implementation\RabbitPhp\EventConsumer($exchange, $connection);
```

**3. Create the Listener and start listening**

```php
$listener = new \HelloFresh\Mailer\Service\Listener($mailer, $producer, $consumer);
//provide the priority that this listener listens for
$service->listen(new \HelloFresh\Mailer\Implementation\Common\Priority\NormalPriority());
```

# Configuration

Mailer assumes some reasonable defaults, but if you want to configure it differently, you can use the `Configuration` class and inject it via constructor to the `Listener` and / or `Enqueuer`.

# Extending

Mailer can be easily extended by creating new implementation of the appropriate interfaces. To implement a different mail sender, simply implement `MailerInterface`. To implement a different message queue, simply implement `EventProducerInterface` and `EventConsumerInterface`.