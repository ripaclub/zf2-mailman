<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MailManTest\Transport\Mandrill;

use MailMan\Transport\Mandrill\Mandrill;
use MailMan\Transport\Mandrill\MandrillOptions;
use MailManTest\TestAsset\ToStringObject;
use Zend\Mail\Message;
use Zend\Mime\Part;
use Zend\Mime\Mime;
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
    public function testSendWithStringBody()
    {
        $message = new Message();
        $message->setBody('test');
        $message->setFrom('test@mandrilltransport.tld');
        $this->mandrill->send($message);
    }

    /**
     * @expectedException \Mandrill_Invalid_Key
     */
    public function testSendMultipart()
    {
        $message = new Message();
        $mime = new MimeMessage();
        $part1 = new Part('one');
        $part1->type = Mime::TYPE_HTML;

        $part2 = new Part('two');
        $part2->type = Mime::TYPE_TEXT;

        $fileInfo = new \finfo(FILEINFO_MIME_TYPE);
        $attachment = __DIR__ . DIRECTORY_SEPARATOR . 'MandrillTest.php';
        $part3 = new Part(fopen($attachment, 'r'));
        $part3->filename = pathinfo($attachment)['basename'];
        $part3->type = $fileInfo->file($attachment);
        $part3->encoding = Mime::ENCODING_BASE64;
        $part3->disposition = Mime::DISPOSITION_ATTACHMENT;

        $mime->addPart($part1);
        $mime->addPart($part2);
        $mime->addPart($part3);

        $message->setBody($mime);
        $message->setFrom('test@mandrilltransport.tld');
        $message->addTo('me@mandrillmegatest.tld');

        $this->mandrill->send($message);
    }

    /**
     * @expectedException \Mandrill_Invalid_Key
     */
    public function testSendObject()
    {
        $message = new Message();
        $body = new ToStringObject();
        $body->addElement('one')->addElement('two')->addElement('three');

        $message->setBody($body);
        $message->setFrom('test@mandrilltransport.tld');
        $this->mandrill->send($message);
    }

    /**
     * @expectedException \MailMan\Exception\InvalidArgumentException
     */
    public function testSendMailWithoutBodyShouldThrowException()
    {
        $message = new Message();
        $this->mandrill->send($message);
    }
}
