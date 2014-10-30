<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MailManTest\Transport\Mandrill;

use MailMan\Transport\Mandrill\MandrillOptions;

/**
 * Class MandrillOptionsTest
 */
class MandrillOptionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MandrillOptions
     */
    protected $mandrillOptions;

    protected function setUp()
    {
        $config = [
            'api_key' => 'MYSECRETMANDRILLKEY',
            'sub_account' => 'my-optional-subaccount-if-any'
        ];
        $this->mandrillOptions = new MandrillOptions($config);
    }

    public function testGetApikey()
    {
        $this->assertEquals('MYSECRETMANDRILLKEY', $this->mandrillOptions->getApikey());
    }

    public function testSetApikey()
    {
        $this->mandrillOptions->setApikey('ANOTHERMANDRILLKEY');
        $this->assertEquals('ANOTHERMANDRILLKEY', $this->mandrillOptions->getApikey());
    }

    public function testGetSubAccount()
    {
        $this->assertEquals('my-optional-subaccount-if-any', $this->mandrillOptions->getSubAccount());
    }

    public function testSetSubAccount()
    {
        $this->mandrillOptions->setSubAccount('another-subaccount');
        $this->assertEquals('another-subaccount', $this->mandrillOptions->getSubAccount());
    }
}
