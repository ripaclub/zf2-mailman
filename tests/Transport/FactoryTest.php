<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Transport;

use MailMan\Exception\DomainException;
use MailMan\Transport\Factory;

/**
 * Class FactoryTest
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Factory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new Factory();
    }

    /**
     * @expectedException \MailMan\Exception\InvalidArgumentException
     */
    public function testCreateWithNonArrayParameterShouldThrowInvalidArgumentException()
    {
        $this->factory->create(null);
    }

    /**
     * @expectedException \MailMan\Exception\InvalidArgumentException
     */
    public function testCreateWithArrayWithoutTypeKeyShouldThrowInvalidArgumentException()
    {
        $this->factory->create([]);
    }
    
    /**
     * @expectedException \MailMan\Exception\DomainException
     */
    public function testCreateWithNonExistentTransportShouldThrowDomainException()
    {
        $this->factory->create(['type' => 'PhantomTransport']);
    }

    public function testCreateWithTraversable()
    {
        $input = new \ArrayObject([
            'type' => 'mandrill',
            'options' => [
                'api_key' => 'MYSECRETMANDRILLKEY',
                'sub_account' => 'my-optional-subaccount-if-any'
            ]
        ]);
        $res = $this->factory->create($input);
        $this->assertInstanceOf('Zend\Mail\Transport\TransportInterface', $res);
    }

    public function testCreateWithOptions()
    {
        $input = [
            'type' => 'mandrill',
            'options' => [
                'api_key' => 'MYSECRETMANDRILLKEY',
                'sub_account' => 'my-optional-subaccount-if-any'
            ]
        ];
        $res = $this->factory->create($input);
        $this->assertInstanceOf('Zend\Mail\Transport\TransportInterface', $res);
    }

    public function testCreateWithoutTransportInterfaceObjectShouldThrowDomainException()
    {
        $input = ['type' => 'fake'];

        $refl = new \ReflectionClass($this->factory);
        $reflProp = $refl->getProperty('classMap');
        $reflProp->setAccessible(true);
        $originalValue = $reflProp->getValue();
        $reflProp->setValue(null, ['fake' => 'MailManTest\TestAsset\FakeTransport']);

        $reflMethod = $refl->getMethod('create');
        try {
            $reflMethod->invoke($refl, $input);
        } catch (DomainException $exc) {
            // Restore original $classMap value
            $reflProp->setValue(null, $originalValue);
            // Manually test the exception instance and message
            $this->assertInstanceOf('\MailMan\Exception\DomainException', $exc);
            $this->assertEquals(
                sprintf(
                    '%s expects the "type" attribute to resolve to a valid %s instance; received "%s"',
                    'MailMan\Transport\Factory::create',
                    'Zend\Mail\Transport\TransportInterface',
                    'MailManTest\TestAsset\FakeTransport'
                ),
                $exc->getMessage()
            );
        }
    }

    public function testCreateWithoutExistingOptionsClassShouldThrowDomainException()
    {
        $this->setExpectedException(
            '\MailMan\Exception\DomainException',
            sprintf(
                '%s expects the "options" attribute to resolve to an existing class; received "%s"',
                'MailMan\Transport\Factory::create',
                'Zend\Mail\Transport\InMemoryOptions'
            )
        );
        $input = [
            'type' => 'inmemory',
            'options' => [
                'opt1' => 'value1'
            ]
        ];
        $this->factory->create($input);
    }

    public function testCreateWithNonAbstractOptionsClassShouldThrowDomainException()
    {
        $input = [
            'type' => 'dummy',
            'options' => [
                'opt1' => 'value1'
            ]
        ];

        $refl = new \ReflectionClass($this->factory);
        $reflProp = $refl->getProperty('classMap');
        $reflProp->setAccessible(true);
        $originalValue = $reflProp->getValue();
        $reflProp->setValue(null, ['dummy' => 'MailManTest\TestAsset\DummyTransport']);

        $reflMethod = $refl->getMethod('create');
        try {
            $reflMethod->invoke($refl, $input);
        } catch (DomainException $exc) {
            // Restore original $classMap value
            $reflProp->setValue(null, $originalValue);
            // Manually test the exception instance and message
            $this->assertInstanceOf('\MailMan\Exception\DomainException', $exc);
            $this->assertEquals(
                sprintf(
                    '%s expects the "options" attribute to resolve to a valid %s instance; received "%s"',
                    'MailMan\Transport\Factory::create',
                    'Zend\Stdlib\AbstractOptions',
                    'MailManTest\TestAsset\DummyTransportOptions'
                ),
                $exc->getMessage()
            );
        }
    }

    public function testCreateWithInvalidTransportClassShouldThrowDomainException()
    {
        $input = [
            'type' => 'almostvalid',
            'options' => [
                'my_opt' => 'myval'
            ]
        ];

        $refl = new \ReflectionClass($this->factory);
        $reflProp = $refl->getProperty('classMap');
        $reflProp->setAccessible(true);
        $originalValue = $reflProp->getValue();
        $reflProp->setValue(null, ['almostvalid' => 'MailManTest\TestAsset\AlmostValidTransport']);

        $reflMethod = $refl->getMethod('create');
        try {
            $reflMethod->invoke($refl, $input);
        } catch (DomainException $exc) {
            // Restore original $classMap value
            $reflProp->setValue(null, $originalValue);
            // Manually test the exception instance and message
            $this->assertInstanceOf('\MailMan\Exception\DomainException', $exc);
            $this->assertEquals(
                sprintf(
                    '%s expects the instance of %s class has a method named "%s" to setting options; method not found',
                    'MailMan\Transport\Factory::create',
                    'MailManTest\TestAsset\AlmostValidTransport',
                    'setOptions'
                ),
                $exc->getMessage()
            );
        }
    }
}
