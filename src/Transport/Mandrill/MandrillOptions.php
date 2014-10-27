<?php
namespace MailMan\Transport\Mandrill;

use Zend\Stdlib\AbstractOptions;

/**
 * Class MandrillOptions
 * @package MailMan\Transport\Mandrill
 */
class MandrillOptions extends AbstractOptions
{
    /**
     * apiKey
     *
     * @var null|string
     */
    protected $apiKey;

    /**
     * Sub Account (if any)
     *
     * @var null|string
     */
    protected $subAccount;

    /**
     * Ctor
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->apiKey = isset($options['apiKey']) ? (string) $options['apiKey'] : null;
        $this->subAccount = isset($options['sub_account']) ? (string) $options['sub_account'] : null;
    }

    /**
     * @return null|string
     */
    public function getapiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param $apiKey
     * @return $this
     */
    public function setapiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getSubAccount()
    {
        return $this->subAccount;
    }

    /**
     * @param $subAccount
     * @return $this
     */
    public function setSubAccount($subAccount)
    {
        $this->subAccount = $subAccount;
        return $this;
    }
}
