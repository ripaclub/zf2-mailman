<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MailMan\Service\Factory;

use MailMan\Exception\RuntimeException;
use MailMan\Mail\Transport\Factory;
use MailMan\Service\MailService;
use Zend\Mail\Message;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MailAbstractServiceFactory
 */
class MailAbstractServiceFactory implements AbstractFactoryInterface
{

    /**
     * Config Key
     * @var string
     */
    protected $configKey = 'mailman';

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

        if (!isset($configNode['transport'])) {
            return false;
        }

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

        $transport = Factory::create($configNode['transport']);

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
            $this->config = [];
            return $this->config;
        }

        $config = $serviceLocator->get('Config');
        if (!isset($config[$this->configKey]) || !is_array($config[$this->configKey])) {
            $this->config = [];
            return $this->config;
        }

        $this->config = $config[$this->configKey];
        return $this->config;
    }
}
