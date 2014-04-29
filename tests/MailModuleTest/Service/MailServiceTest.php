<?php

namespace MailModuleTest;

use MailModule\Service\MailService;
use Zend\Mail\Message;
use Zend\Mail\Transport\Null;

/**
 * Class MailServiceTest
 *
 * @author Lorenzo Fontana <fontanalorenzo@me.com>
 */
class MailServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var MailService
     */
    protected $mailService;


    protected function setUp()
    {
        $message = new Message();
        $message->setFrom('from@someone.tld');

        $transport = new Null();

        $mailService = new MailService($message, $transport);
        $message = $mailService->getMessage();
        $message->addTo('to@someone.tld');
        $message->addBcc('hidden@someone.tld');
        $message->addCc('copy@someone.tld');

        $this->mailService = $mailService;
    }

    public function testGetMessage()
    {
        $this->assertInstanceOf('\Zend\Mail\Message', $this->mailService->getMessage());
    }

    public function testSetAttachments()
    {
        $attachments = ['/first/path', '/second/path', '/third/path'];
        $this->mailService->setAttachments($attachments);
        $this->assertEquals($this->mailService->getAttachments(), $attachments);
    }

    public function testAddAttachment()
    {
        $attachment = '/my/attachment/path';
        $this->mailService->addAttachment($attachment);
        $this->assertEquals($this->mailService->getAttachments(), array($attachment));
    }

    public function testSetBodyHtml()
    {
        $body = <<<HTML
<h1>This is the mail body</h1>
Woooooooooooooooooooohoooooooo
HTML;
        $this->mailService->setBody($body);

        /** @var \Zend\Mime\Message $body */
        $body = $this->mailService->getMessage()->getBody();

        $this->assertInstanceOf('\Zend\Mime\Message', $body);
        $this->assertCount(1, $body->getParts());
    }

    public function testSetBody()
    {
        $bodyPart = new \Zend\Mime\Message();
        $bodyMessage1 = new \Zend\Mime\Part('first part');
        $bodyMessage1->type = 'text/html';

        $bodyMessage2 = new \Zend\Mime\Part('second part');
        $bodyMessage2->type = 'text/html';

        $bodyPart->setParts(array($bodyMessage1, $bodyMessage2));


        $this->mailService->setBody($bodyPart);

        /** @var \Zend\Mime\Message $body */
        $body = $this->mailService->getMessage()->getBody();

        $this->assertInstanceOf('\Zend\Mime\Message', $body);
        $this->assertCount(2, $body->getParts());

    }

    public function testSend()
    {
        $this->assertNull($this->mailService->send());
    }
}
