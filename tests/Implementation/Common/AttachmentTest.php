<?php

namespace Tests\Implementation\Common;

use HelloFresh\Mailer\Implementation\Common\Attachment;

class AttachmentTest extends \PHPUnit_Framework_TestCase
{
    public function testToAndFromArray()
    {
        $original = Factory::createAttachment();
        $array = $original->toArray();

        /** @var Attachment $clone */
        $clone = Attachment::fromArray($array);

        $this->assertTrue($clone instanceof Attachment);
        $this->assertEquals($clone, $original);
    }

    /**
     * @expectedException HelloFresh\Mailer\Exception\SerializationException
     * @dataProvider arrayValidationDataProvider
     * @param int $arrayKey
     * @param mixed $replacement
     */
    public function testArrayValidation($arrayKey, $replacement)
    {
        $original = Factory::createAttachment();
        $array = $original->toArray();

        $array[$arrayKey] = $replacement; //replace with int - must throw an exception
        Attachment::fromArray($array);
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
