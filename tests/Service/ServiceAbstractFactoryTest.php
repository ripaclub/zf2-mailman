<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MailManTest\Service;

use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

/**
 * Class ServiceAbstractFactoryTest
 */
class ServiceAbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceManager
     */
    protected $sManager;

    public function setUp()
    {
        $this->sManager = new ServiceManager(
            new ServiceManagerConfig(
                [
                    'abstract_factories' => [
                        'MailMan\Service\ServiceAbstractFactory',
                    ],
                ]
            )
        );
    }

    public function testCanCreateServiceWithNullConfig()
    {
        $this->sManager->setService('Config', null);
        $this->assertFalse($this->sManager->has('Test'));
    }

    public function testCanCreateServiceWithEmptyArrayConfig()
    {
        $this->sManager->setService('Config', []);
        $this->assertFalse($this->sManager->has('Test'));
    }

    public function testCanCreateService()
    {
        $config = [
            'mailman' => [
                'MailMan\SMTP\Basic' => [
                    'transport' => [
                        'type' => 'smtp',
                    ],
                ],
            ]
        ];
        $this->sManager->setService('Config', $config);
        $this->assertTrue($this->sManager->has('MailMan\SMTP\Basic'));
        $this->assertFalse($this->sManager->has('MailMan\SMTP'));
    }

    public function testCheckHasTransportConfigWithoutTypeNode()
    {
        $config = [
            'mailman' => [
                'MailMan\SMTP\Basic' => [
                    'transport' => [
                    ],
                ],
            ]
        ];
        $this->sManager->setService('Config', $config);
        $this->assertFalse($this->sManager->has('MailMan\SMTP\Basic'));
    }

    public function testCreateService()
    {
        $config = [
            'mailman' => [
                'Mandrill\Complete' => [
                    'default_sender' => 'test@mail.com',
                    'additional_info' => [
                        'some' => 'thing'
                    ],
                    'transport' => [
                        'type' => 'mandrill',
                        'options' => [
                            'api_key' => 'MYSECRETMANDRILLKEY',
                            'sub_account' => 'my-optional-subaccount-if-any'
                        ],
                    ],
                ],
            ],
        ];
        $this->sManager->setService('Config', $config);
        $this->assertTrue($this->sManager->has('Mandrill\Complete'));
        $mailService = $this->sManager->get('Mandrill\Complete');
        $this->assertInstanceOf('MailMan\Service\MailService', $mailService);
    }
}
