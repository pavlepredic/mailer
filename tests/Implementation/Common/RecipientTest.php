<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\Recipient;

class RecipientTest extends \PHPUnit_Framework_TestCase
{
    public function testToAndFromArray()
    {
        $original = Factory::createRecipient();
        $array = $original->toArray();

        /** @var Recipient $clone */
        $clone = Recipient::fromArray($array);

        $this->assertTrue($clone instanceof Recipient);
        $this->assertEquals($original, $clone);
    }

    /**
     * @expectedException HelloFresh\Mailer\Exception\SerializationException
     * @dataProvider arrayValidationDataProvider
     * @param int $arrayKey
     * @param mixed $replacement
     */
    public function testArrayValidation($arrayKey, $replacement)
    {
        $original = Factory::createRecipient();
        $array = $original->toArray();

        $array[$arrayKey] = $replacement;
        Recipient::fromArray($array);
    }

    public function arrayValidationDataProvider()
    {
        return [
            [0, 1],
            [1, 1],
            [2, 1],
        ];
    }
}
