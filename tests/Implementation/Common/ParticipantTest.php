<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\Participant;

class ParticipantTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $first
     * @param array $second
     * @param boolean $expected
     * @dataProvider participantProvider
     */
    public function testEquality(array $first, array $second, $expected)
    {
        $this->assertEquals($expected, $this->loadParticipant($first)->equals($this->loadParticipant($second)));
    }

    public function participantProvider()
    {
        return [
            [['name', 'email'], ['name', 'email'], true],
            [['name', 'email'], ['name1', 'email'], false],
            [['name', 'email'], ['name', 'email1'], false],
        ];
    }

    private function loadParticipant(array $data)
    {
        $participant = new Participant();
        $participant->setName($data[0]);
        $participant->setEmail($data[1]);
        return $participant;
    }
}
