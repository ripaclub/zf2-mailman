<?php

namespace MailModule\Service\Factory;

use MailModule\Mail\Transport\MandrillOptions;
use MailModule\Service\MailService;
use MailModule\Exception\RuntimeException;
use MailModule\Mail\Transport\Mandrill;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MailAbstractServiceFactory
 *
 * @author Lorenzo Fontana <fontanalorenzo@me.com>
 */
class MailAbstractServiceFactory implements AbstractFactoryInterface
{

    /**
     * Config Key
     * @var string
     */
    protected $configKey = 'mail_module';

    /**
     * Config
     * @var array
     */
    protected $config;


    /**
     * {@inheritdoc}
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $this->getConfig($serviceLocator);

        if (empty($config)) {
            return false;
        }

        if (!array_key_exists($requestedName, $config)) {
            return false;
        }

        $configNode = $config[$requestedName];

        if (!is_array($configNode['transport'])) {
            return false;
        }

        if (!isset($configNode['transport']['type'])) {
            return false;
        }

        if (!isset($configNode['transport']['options'])) {
            return false;
        }

        return true;

    }


    /**
     * Create Mail Service With Name
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @param  string $name
     * @param  string $requestedName
     * @throws RuntimeException
     * @return MailService
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $this->getConfig($serviceLocator);
        $configNode = $config[$requestedName];

        $message = new Message();
        if (isset($configNode['default_sender'])) {
            $message->setFrom($configNode['default_sender']);
        }

        $transport = null;
        switch ($configNode['transport']['type']) {
            case 'smtp':
                $transport = new Smtp();
                $smtpOptions = new SmtpOptions($configNode['transport']['options']);
                $transport->setOptions($smtpOptions);
                break;
            case 'mandrill':
                $transport = new Mandrill();
                $mandrillOptions = new MandrillOptions($configNode['transport']['options']);
                $transport->setOptions($mandrillOptions);
                break;
        }

        if (!$transport) {
            throw new RuntimeException(
                sprintf(
                    '%s: Provided transport type cannot be created',
                    __METHOD__
                )
            );
        }

        $mailService = new MailService($message, $transport);
        return $mailService;

    }


    /**
     * Get model configuration, if any
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return array
     */
    protected function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        if ($this->config !== null) {
            return $this->config;
        }

        if (!$serviceLocator->has('Config')) {
            $this->config = array();
            return $this->config;
        }

        $config = $serviceLocator->get('Config');
        if (!isset($config[$this->configKey])
            || !is_array($config[$this->configKey])
        ) {
            $this->config = array();
            return $this->config;
        }

        $this->config = $config[$this->configKey];
        return $this->config;
    }
}
