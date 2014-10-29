<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MailManTest\TestAsset;

use Zend\Stdlib\AbstractOptions;

/**
 * Class AlmostValidTransportOptions
 */
class AlmostValidTransportOptions extends AbstractOptions
{
    protected $myOpt;

    /**
     * @param $myVal
     */
    public function setMyOpt($myVal)
    {
        $this->myOpt = $myVal;
    }
}
