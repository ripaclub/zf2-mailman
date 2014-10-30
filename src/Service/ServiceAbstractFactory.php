<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MailMan\Service;

use MailMan\Transport\Factory;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ServiceAbstractFactory
 */
class ServiceAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Config Key
     *
     * @var string
     */
    protected $configKey = 'mailman';

    /**
     * Config
     *
     * @var array
     */
    protected $config;

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $this->getConfig($serviceLocator);

        if (empty($config)) {
            return false;
        }

        $serviceConfig = $this->checkHasRequestedNameConfig($config, $requestedName);
        $transportConfig = $this->checkHasTransportConfig($config, $requestedName, $serviceLocator);

        return $serviceConfig && $transportConfig;
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $this->getConfig($serviceLocator)[$requestedName];

        $defaultSender = isset($config['default_sender']) ? $config['default_sender'] : null;
        $transport = Factory::create($config['transport']);

        $serviceClient = new MailService($transport, $defaultSender);
        $serviceClient->setAdditionalInfo(isset($config['additional_info']) ? $config['additional_info'] : []);

        return $serviceClient;
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

    /**
     * Check if has node config
     *
     * @param $config
     * @param $requestedName
     * @return bool
     */
    protected function checkHasRequestedNameConfig($config, $requestedName)
    {
        if (isset($config[$requestedName]) && is_array($config[$requestedName]) && !empty($config[$requestedName])) {
            return true;
        }
        return false;
    }

    /**
     * Check if has node config
     *
     * @param $config
     * @param $requestedName
     * @return bool
     */
    protected function checkHasTransportConfig($config, $requestedName)
    {
        if (isset($config[$requestedName]['transport'])
            && is_array($config[$requestedName]['transport'])
            && !empty($config[$requestedName]['transport'])
            && isset($config[$requestedName]['transport']['type'])
            && !empty($config[$requestedName]['transport']['type'])
            && is_string($config[$requestedName]['transport']['type'])
        ) {
            return true;
        }
        return false;
    }
}
