<?php

namespace MailManTest\Mail\Transport;

use MailMan\Mail\Transport\Mandrill;
use MailMan\Mail\Transport\MandrillOptions;
use MailManTest\TestAsset\ToStringObject;
use Zend\Mail\Message;
use Zend\Mime\Part;
use Zend\Mime\Message as MimeMessage;

/**
 * Class MandrillTest
 */
class MandrillTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mandrill
     */
    protected $mandrill;

    protected function setUp()
    {
        $transport = new Mandrill();
        $options = new MandrillOptions();
        $options->setApikey('test');
        $transport->setOptions($options);
        $this->mandrill = $transport;
    }

    /**
     * @expectedException \Mandrill_Invalid_Key
     */
    public function testSend()
    {
        $message = new Message();
        $message->setBody('test');
        $message->setFrom('test@mandrilltransport.tld');
        $this->mandrill->send($message);
    }

    /**
     * @expectedException \Mandrill_Invalid_Key
     */
    public function testSendMime()
    {
        $message = new Message();
        $mime = new MimeMessage();
        $part = new Part('test');
        $mime->addPart($part);
        $message->setBody($mime);
        $message->setFrom('test@mandrilltransport.tld');
        $message->addTo('me@mandrillmegatest.tld');
        $this->mandrill->send($message);
    }

    /**
     * @expectedException \Mandrill_Invalid_Key
     */
    public function testSendAnotherObject()
    {
        $message = new Message();
        $body = new ToStringObject();
        $body->addElement('one')->addElement('two')->addElement('three');

        $message->setBody($body);
        $message->setFrom('test@mandrilltransport.tld');
        $this->mandrill->send($message);
    }

}
