<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Contract\MessageInterface;
use HelloFresh\Mailer\Implementation\Common\JsonSerializer;

class JsonSerializerTest extends \PHPUnit_Framework_TestCase
{
    public function testSerialize()
    {
        $message = Factory::createMessage();

        $serializer = new JsonSerializer();
        $serialized = $serializer->serialize($message);

        /** @var MessageInterface $unserialized */
        $unserialized = $serializer->unserialize($serialized);
        $this->assertTrue($unserialized instanceof MessageInterface);
        $this->assertEquals($message, $unserialized);
    }
}
