<?php

namespace MailModule\Mail\Transport;

use Zend\Stdlib\AbstractOptions;

/**
 * Class MandrillOptions
 *
 * @author Lorenzo Fontana <fontanalorenzo@me.com>
 */
class MandrillOptions extends AbstractOptions
{
    /**
     * Apikey
     *
     * @var null|string
     */
    protected $apikey;

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
        $this->apikey = isset($options['apikey']) ? (string)$options['apikey'] : null;
        $this->subAccount = isset($options['sub_account']) ? (string)$options['sub_account'] : null;
    }

    /**
     * Get Apikey
     *
     * @return null|string
     */
    public function getApikey()
    {
        return $this->apikey;
    }

    /**
     * Set Apikey
     *
     * @param $apikey
     * @return $this
     */
    public function setApikey($apikey)
    {
        $this->apikey = $apikey;
        return $this;
    }

    /**
     * Get SubAccount
     *
     * @return null|string
     */
    public function getSubAccount()
    {
        return $this->subAccount;
    }

    /**
     * Set SubAccount
     *
     * @param $subAccount
     * @return $this
     */
    public function setSubAccount($subAccount)
    {
        $this->subAccount = $subAccount;
        return $this;
    }
}
