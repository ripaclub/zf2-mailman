<?php

namespace MailModule\Service\Factory;

use Zend\ServiceManager\ServiceManager;


/**
 * Class MailAbstractServiceFactoryTest
 *
 * @author Lorenzo Fontana <fontanalorenzo@me.com>
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
     * @dataProvider configDataProvider
     */
    public function testCanCreateService($config)
    {
        $this->sm->setService('Config', $config);

        $sf = new MailAbstractServiceFactory();
        $sf->canCreateServiceWithName($this->sm, 'gmail', 'gmail');
    }


    public function configDataProvider()
    {
        return [
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
                            'transport' => [
                                'type' => 'smtp',
                                'options' => [
                                    'host' => 'smtp.gmail.com',
                                    'port' => '587',
                                    'connection_class' => 'login',
                                    'connection_config' => [
                                        'ssl' => 'tls',
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
                            'transport' => ''
                        ],
                    ],
                ],
            ],
            [
                [
                    'mail_module' => [
                        'gmail' => [
                            'default_sender' => 'mailmoduletest@gmail.com',
                            'transport' => [

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
                            'transport' => [
                                'type' => 'smtp'
                            ]
                        ],
                    ],
                ],
            ]

        ];
    }
}
