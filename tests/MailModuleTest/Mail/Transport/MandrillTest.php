<?php

namespace MailModuleTest\Mail\Transport;

use MailModule\Mail\Transport\Mandrill;
use MailModule\Mail\Transport\MandrillOptions;
use MailModuleTest\TestAsset\ToStringObject;
use Zend\Mail\Message;
use Zend\Mime\Part;

/**
 * Class MandrillTest
 *
 * @author Lorenzo Fontana <fontanalorenzo@me.com>
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
        $mime = new \Zend\Mime\Message();
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

