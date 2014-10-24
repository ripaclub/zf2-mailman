<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MailMan\Mail\Transport;

use Zend\Stdlib\AbstractOptions;

/**
 * Class MandrillOptions
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
