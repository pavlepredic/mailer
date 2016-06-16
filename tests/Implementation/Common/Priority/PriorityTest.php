<?php

namespace Tests\Implementation\Common\Priority;

use HelloFresh\Mailer\Implementation\Common\Priority\HighPriority;
use HelloFresh\Mailer\Implementation\Common\Priority\LowPriority;
use HelloFresh\Mailer\Implementation\Common\Priority\NormalPriority;
use HelloFresh\Mailer\Implementation\Common\Priority\Priority;

class PriorityTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $this->assertEquals('high_priority', (new HighPriority())->toString());
        $this->assertEquals('normal_priority', (new NormalPriority())->toString());
        $this->assertEquals('low_priority', (new LowPriority())->toString());
    }

    public function testFromValidString()
    {
        $this->assertTrue(Priority::fromString('high_priority') instanceof HighPriority);
        $this->assertTrue(Priority::fromString('normal_priority') instanceof NormalPriority);
        $this->assertTrue(Priority::fromString('low_priority') instanceof LowPriority);
    }

    /**
     * @expectedException HelloFresh\Mailer\Exception\InvalidArgumentException
     */
    public function testFromInvalidString()
    {
        Priority::fromString('no_such_priority');
    }
}
