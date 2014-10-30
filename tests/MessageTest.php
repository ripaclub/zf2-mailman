<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MailManTest;

use MailMan\Message;
use MailManTest\TestAsset\ToStringObject;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part;

/**
 * Class MessageTest
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Message
     */
    protected $mex;

    public function setUp()
    {
        $this->mex = new Message();
    }

    public function testSetBody()
    {
        $this->assertSame($this->mex, $this->mex->setBody(null));
        $this->assertSame(null, $this->mex->getBody());

        $this->assertSame($this->mex, $this->mex->setBody('string'));
        $this->assertSame('string', $this->mex->getBody());

        $mimeMessage = new MimeMessage();
        $this->assertSame($this->mex, $this->mex->setBody($mimeMessage));
        $this->assertSame($mimeMessage, $this->mex->getBody());

        $toStringObj = new ToStringObject();
        $this->assertSame($this->mex, $this->mex->setBody($toStringObj));
        $this->assertSame($toStringObj, $this->mex->getBody());
    }

    public function testSetBodyHeaders()
    {
        $mimeMessage = new MimeMessage();
        $textPart1 = new Part('Text part 1');
        $textPart1->type = Mime::TYPE_TEXT;
        $textPart1->id = 'FAKEID';
        $mimeMessage->addPart($textPart1);
        $this->mex->setBody($mimeMessage);

        $headers = $this->mex->getHeaders();
        $this->assertTrue($headers->has('Content-ID'));
        $this->assertSame('<' . $textPart1->id . '>', $headers->get('Content-ID')->getFieldValue());
        $this->assertTrue($headers->has('Content-Transfer-Encoding'));

        // Multipart
        $textPart2 = new Part('Text part 2');
        $textPart2->type = Mime::TYPE_TEXT;
        $textPart2->id = 'ID2';
        $mimeMessage->addPart($textPart2);
        $this->mex->setBody($mimeMessage);
        $this->assertFalse($headers->has('Content-ID'));
        $this->assertFalse($headers->has('Content-Transfer-Encoding'));
    }

    public function testAddHtmlPart()
    {
        $this->mex->addHtmlPart('I will be wrapped in HTML!');
        /** @var $body \Zend\Mime\Message */
        $body = $this->mex->getBody();
        $this->assertInstanceOf('\Zend\Mime\Message', $body);
        $parts = $body->getParts();
        $this->assertCount(1, $parts);
        $this->assertEquals(Mime::TYPE_HTML, $parts[0]->type);
    }

    public function testAddHtmlPartShouldThrowRuntimeExceptionWhenBodyIsNotAZendMimeMessage()
    {
        $this->setExpectedException(
            'MailMan\Exception\RuntimeException',
            sprintf(
                'Body must be an instance of %s not string',
                'Zend\Mime\Message'
            )
        );
        $this->mex->setBody('Not a Zend\Mime\Message body');
        $this->mex->addHtmlPart('Hello World');
    }

    public function testAddTextPart()
    {
        $this->mex->addTextPart('I am a text part');
        /** @var $body \Zend\Mime\Message */
        $body = $this->mex->getBody();
        $this->assertInstanceOf('\Zend\Mime\Message', $body);
        $parts = $body->getParts();
        $this->assertCount(1, $parts);
        $this->assertEquals(Mime::TYPE_TEXT, $parts[0]->type);
    }

    public function testAddTextPartShouldThrowRuntimeExceptionWhenBodyIsNotAZendMimeMessage()
    {
        $this->setExpectedException(
            'MailMan\Exception\RuntimeException',
            sprintf(
                'Body must be an instance of %s not string',
                'Zend\Mime\Message'
            )
        );
        $this->mex->setBody('Not a Zend\Mime\Message body');
        $this->mex->addTextPart('Hello World');
    }

    public function testAddAttachment()
    {
        $currentMex = $this->mex->addAttachment(__DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php');
        /** @var $body \Zend\Mime\Message */
        $body = $this->mex->getBody();
        $this->assertInstanceOf('\Zend\Mime\Message', $body);
        $parts = $body->getParts();
        $this->assertCount(1, $parts);
        $this->assertSame($this->mex, $currentMex);
        $this->assertInstanceOf('\MailMan\Message', $currentMex);
    }

    public function testAddAttachmentPartShouldThrowRuntimeExceptionWhenBodyIsNotAZendMimeMessage()
    {
        $this->setExpectedException(
            'MailMan\Exception\RuntimeException',
            sprintf(
                'Body must be an instance of %s not string',
                'Zend\Mime\Message'
            )
        );
        $this->mex->setBody('Not a Zend\Mime\Message body');
        $this->mex->addAttachment(__DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php');
    }

    /**
     * @expectedException \MailMan\Exception\InvalidArgumentException
     */
    public function testAddAttachmentThatIsNotAFileShouldThrowInvalidArgumentException()
    {
        $this->mex->addAttachment('hello exception!');
    }
}
