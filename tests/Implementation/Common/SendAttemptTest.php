<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\SendAttempt;

class SendAttemptTest extends \PHPUnit_Framework_TestCase
{
    public function testToAndFromArray()
    {
        $original = Factory::createSendAttempt();
        $array = $original->toArray();

        /** @var SendAttempt $clone */
        $clone = SendAttempt::fromArray($array);

        $this->assertTrue($clone instanceof SendAttempt);
        $this->assertEquals($original, $clone);
    }

    /**
     * @param $before
     * @param $now
     * @dataProvider getElapsedTimeProvider
     */
    public function testGetElapsedTime($before, $now, $elapsed)
    {
        $attempt = new SendAttempt(new \DateTime($before));
        $this->assertEquals($elapsed, $attempt->getElapsedTime(new \DateTime($now)));
    }

    public function getElapsedTimeProvider()
    {
        return [
            ['2016-01-01 10:00:00', '2016-01-01 10:01:00', 60],
            ['2016-01-01 10:00:00', '2016-01-01 11:00:00', 3600],
            ['2016-01-01 10:00:00', '2016-01-02 10:00:00', 86400],
            ['2016-01-01 10:00:00', '2016-01-01 09:00:00', -3600], //test edge case
        ];
    }
}
