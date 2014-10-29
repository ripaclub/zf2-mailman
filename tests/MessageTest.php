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
use Zend\Mail\Headers;
use Zend\Mime\Mime;

/**
 * Class MessageTest
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var $mex Message
     */
    protected $mex;

    public function setUp()
    {
        $this->mex = new Message();
    }

    public function testPrepareBodyMessage()
    {
        $body = $this->mex->prepareBodyMessage();
        $this->assertInstanceOf('\Zend\Mime\Message', $body);
        $this->assertCount(0, $body->getParts());
    }

    /**
     * @expectedException \MailMan\Exception\RuntimeException
     */
    public function testPrepareBodyMessageShouldThrowRuntimExceptionWhenBodyIsNotAZendMimeMessage()
    {
        $this->mex->setBody('Not a \Zend\Mime\Message body!');
        $this->mex->prepareBodyMessage();
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

    public function testAddAttachment()
    {
        $currentMex = $this->mex->addAttachment(__DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php');
        /** @var $body \Zend\Mime\Message */
        $body = $this->mex->getBody();
        $parts = $body->getParts();
        $this->assertCount(1, $parts);
        $this->assertSame($this->mex, $currentMex);
        $this->assertInstanceOf('\MailMan\Message', $currentMex);
    }

    /**
     * @expectedException \MailMan\Exception\InvalidArgumentException
     */
    public function testAddAttachmentThatIsNotAFileShouldThrowInvalidArgumentException()
    {
        $this->mex->addAttachment('hello exception!');
    }
}
