<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MailMan\Service\Factory;

use Zend\ServiceManager\ServiceManager;

/**
 * Class MailAbstractServiceFactoryTest
 */
class MailAbstractServiceFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $sm;

    public function setUp()
    {
        $this->sm = new ServiceManager();
    }

    /**
     * @dataProvider canCreateConfigDataProvider
     */
    public function testCanCreateService($config)
    {
        $this->sm->setService('Config', $config);

        $sf = new MailAbstractServiceFactory();
        $sf->canCreateServiceWithName($this->sm, 'gmail', 'gmail');
    }


    public function testCanCreateServiceWithoutConfig()
    {
        $sf = new MailAbstractServiceFactory();
        $sf->canCreateServiceWithName($this->sm, 'gmail', 'gmail');
    }

    public function testCanCreateServiceDouble()
    {
        $config = [
            'mail_module' => [
                'gmail' => [
                    'default_sender' => 'mailmoduletest@gmail.com',
                    'transport'      => [
                        'type'    => 'smtp',
                        'options' => [
                            'host'              => 'smtp.gmail.com',
                            'port'              => '587',
                            'connection_class'  => 'login',
                            'connection_config' => [
                                'ssl'      => 'tls',
                                'username' => 'mailmoduletest@gmail.com',
                                'password' => 'MYSECRETPASSWORD',
                            ]
                        ]
                    ],
                ],
            ],
        ];

        $this->sm->setService('Config', $config);

        $sf = new MailAbstractServiceFactory();
        $sf->canCreateServiceWithName($this->sm, 'gmail', 'gmail');
        $sf->canCreateServiceWithName($this->sm, 'gmail', 'gmail');
    }


    /**
     * @dataProvider creationConfigDataProvider
     */
    public function testCreateServiceWithName($config)
    {

        $node = new \ArrayIterator($config['mail_module']);
        $this->sm->setService('Config', $config);

        $sf = new MailAbstractServiceFactory();
        $sf->createServiceWithName($this->sm, $node->key(), $node->key());
    }

    /**
     * @expectedException \MailMan\Exception\DomainException
     */
    public function testCreateServiceWithNameThrowingException()
    {

        $config = [
            'mail_module' => [
                'anothertathdoesntwork' => [
                    'default_sender' => 'mailmoduletest@thatdoesnotwork.com',
                    'transport'      => [
                        'type'    => 'thisdoesntwork',
                        'options' => []
                    ],
                ],
            ],
        ];

        $node = new \ArrayIterator($config['mail_module']);
        $this->sm->setService('Config', $config);

        $sf = new MailAbstractServiceFactory();
        $sf->createServiceWithName($this->sm, $node->key(), $node->key());
    }


    /**
     * Config Data Provider
     * Data provider used to test if the service can be created
     *
     * @return array
     */
    public function canCreateConfigDataProvider()
    {
        return [
            [
                [
                    'another_key' => [],
                ]
            ],
            [
                [
                    'mail_module' => [],
                ]
            ],
            [
                [
                    'mail_module' => [
                        'gmail' => [
                            'default_sender' => 'mailmoduletest@gmail.com',
                            'transport'      => [
                                'type'    => 'smtp',
                                'options' => [
                                    'host'              => 'smtp.gmail.com',
                                    'port'              => '587',
                                    'connection_class'  => 'login',
                                    'connection_config' => [
                                        'ssl'      => 'tls',
                                        'username' => 'mailmoduletest@gmail.com',
                                        'password' => 'MYSECRETPASSWORD',
                                    ]
                                ]
                            ],
                        ],
                    ],
                ]
            ],
            [
                [
                    'mail_module' => [
                        'another' => [],
                    ],
                ]
            ],
            [
                [
                    'mail_module' => [
                        'gmail' => [
                            'default_sender' => 'mailmoduletest@gmail.com',
                        ],
                    ],
                ]
            ],
            [
                [
                    'mail_module' => [
                        'gmail' => [
                            'default_sender' => 'mailmoduletest@gmail.com',
                            'transport'      => ''
                        ],
                    ],
                ],
            ],
            [
                [
                    'mail_module' => [
                        'gmail' => [
                            'default_sender' => 'mailmoduletest@gmail.com',
                            'transport'      => [

                            ]
                        ],
                    ],
                ],
            ],
            [
                [
                    'mail_module' => [
                        'gmail' => [
                            'default_sender' => 'mailmoduletest@gmail.com',
                            'transport'      => [
                                'type' => 'smtp'
                            ]
                        ],
                    ],
                ],
            ]

        ];
    }

    /**
     * Creation Config Data Provider
     * Data provider used to simulate creation of mail services.
     *
     * @return array
     */
    public function creationConfigDataProvider()
    {

        return [
            [
                [
                    'mail_module' => [
                        'gmail' => [
                            'default_sender' => 'mailmoduletest@gmail.com',
                            'transport'      => [
                                'type'    => 'smtp',
                                'options' => [
                                    'host'              => 'smtp.gmail.com',
                                    'port'              => '587',
                                    'connection_class'  => 'login',
                                    'connection_config' => [
                                        'ssl'      => 'tls',
                                        'username' => 'mailmoduletest@gmail.com',
                                        'password' => 'MYSECRETPASSWORD',
                                    ]
                                ]
                            ],
                        ],
                    ],
                ],
            ],
            [
                [
                    'mail_module' => [
                        'mandrill' => [
                            'default_sender' => 'mailmoduletest@yourmandrillsendingdomain.com',
                            'transport'      => [
                                'type'    => 'mandrill',
                                'options' => [
                                    'apikey'      => 'YOURSECRETAPIKEY',
                                    'sub_account' => 'subaccount'
                                ]
                            ],
                        ],
                    ],
                ]
            ]
        ];

    }
}
