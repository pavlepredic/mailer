<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\Header;

class HeaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $first
     * @param array $second
     * @param boolean $expected
     * @dataProvider headerProvider
     */
    public function testEquality(array $first, array $second, $expected)
    {
        $this->assertEquals($expected, $this->loadHeader($first)->equals($this->loadHeader($second)));
    }

    public function headerProvider()
    {
        return [
            [['name', 'value'], ['name', 'value'], true],
            [['name', 'value'], ['name1', 'value'], false],
            [['name', 'value'], ['name', 'value1'], false],
        ];
    }

    private function loadHeader(array $data)
    {
        $header = new Header();
        $header->setName($data[0]);
        $header->setValue($data[1]);
        return $header;
    }
}
