<?php

namespace MailModuleTest\Mail\Transport;

use MailModule\Mail\Transport\MandrillOptions;

/**
 * Class MandrillOptionsTest
 *
 * @author Lorenzo Fontana <fontanalorenzo@me.com>
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
            'apikey'      => 'MYSECRETMANDRILLKEY',
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
