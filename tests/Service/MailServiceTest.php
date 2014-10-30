<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MailManTest\Service;

use MailMan\Message as MailManMessage;
use MailMan\Service\MailInterface;
use MailMan\Service\MailService;
use Zend\Mail\Transport\TransportInterface;

/**
 * Class MailServiceTest
 */
class MailServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $transportMock;

    /**
     * @var MailInterface
     */
    protected $mailService;

    public function setUp()
    {
        $this->transportMock = $this->getMockBuilder('Zend\Mail\Transport\TransportInterface')
                                    ->setMethods(['send'])
                                    ->getMockForAbstractClass();
        /** @var $transport TransportInterface */
        $transport = $this->transportMock;
        $this->mailService = new MailService($transport, 'default@mail.com');
    }

    public function testConstructor()
    {
        $this->assertInstanceOf('MailMan\Service\MailService', $this->mailService);
    }

    public function testSend()
    {
        $mex = new MailManMessage();

        $this->transportMock->expects($this->at(0))
                            ->method('send')
                            ->with($this->equalTo($mex));

        $this->mailService->send($mex);
    }

    public function testSettingAndGettingAdditionalInfo()
    {
        $input = ['additional' => 'info'];
        $this->mailService->setAdditionalInfo($input);
        $this->assertEquals($input, $this->mailService->getAdditionalInfo());
    }
}
