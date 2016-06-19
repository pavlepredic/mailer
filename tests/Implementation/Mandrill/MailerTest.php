<?php

namespace Tests\Implementation\Mandrill;

use HelloFresh\Mailer\Contract\ResponseInterface;
use HelloFresh\Mailer\Exception\ResponseException;
use HelloFresh\Mailer\Implementation\Mandrill\Mailer;
use HelloFresh\Mailer\Implementation\Mandrill\MessageDecorator;
use Tests\Implementation\Common\Factory;

class MailerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $responseStatus
     * @param string $responseError
     * @param bool $isSuccessful
     * @dataProvider responseProvider
     */
    public function testSend($responseStatus, $responseError, $isSuccessful)
    {
        $message = Factory::createMessage();
        $message->setTemplate(null);
        $mandrillMessage = new MessageDecorator($message);
        $sender = $this->prophesize('\Mandrill_Messages');
        $response = [
            $message->getRecipient()->getEmail() => [
                'status' => $responseStatus,
                'reject_reason' => $responseError,
            ],
        ];
        $sender
            ->send($mandrillMessage->toArray())
            ->shouldBeCalled()
            ->willReturn($response)
        ;

        $mailer = new Mailer($sender->reveal());
        $responseObject = $mailer->send($message);
        $this->assertTrue($responseObject instanceof ResponseInterface);
        $this->assertEquals($responseStatus, $responseObject->getStatus());
        $this->assertEquals($responseError, $responseObject->getError());
        $this->assertEquals($isSuccessful, $responseObject->isSuccessful());
    }

    /**
     * @param string $responseStatus
     * @param string $responseError
     * @param bool $isSuccessful
     * @dataProvider responseProvider
     */
    public function testSendTemplate($responseStatus, $responseError, $isSuccessful)
    {
        $message = Factory::createMessage();
        $mandrillMessage = new MessageDecorator($message);
        $sender = $this->prophesize('\Mandrill_Messages');
        $response = [
            $message->getRecipient()->getEmail() => [
                'status' => $responseStatus,
                'reject_reason' => $responseError,
            ],
        ];
        $sender
            ->sendTemplate($message->getTemplate(), [], $mandrillMessage->toArray())
            ->shouldBeCalled()
            ->willReturn($response)
        ;

        $mailer = new Mailer($sender->reveal());
        $responseObject = $mailer->send($message);
        $this->assertTrue($responseObject instanceof ResponseInterface);
        $this->assertEquals($responseStatus, $responseObject->getStatus());
        $this->assertEquals($responseError, $responseObject->getError());
        $this->assertEquals($isSuccessful, $responseObject->isSuccessful());
    }

    /**
     * @expectedException HelloFresh\Mailer\Exception\ResponseException
     */
    public function testSendWithException()
    {
        $message = Factory::createMessage();
        $mandrillMessage = new MessageDecorator($message);
        $sender = $this->prophesize('\Mandrill_Messages');
        $sender
            ->sendTemplate($message->getTemplate(), [], $mandrillMessage->toArray())
            ->shouldBeCalled()
            ->willThrow(new \Mandrill_Error())
        ;

        $mailer = new Mailer($sender->reveal());
        $mailer->send($message);
    }


    public function responseProvider()
    {
        return [
            ['sent', null, true],
            ['rejected', 'hard-bounce', false],
        ];
    }
}
