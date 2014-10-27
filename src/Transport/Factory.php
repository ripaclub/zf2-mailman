<?php
namespace MailMan\Transport;

use MailMan\Exception;
use Zend\Mail\Transport\TransportInterface;
use Zend\Stdlib\AbstractOptions;
use Zend\Stdlib\ArrayUtils;

/**
 * Class Factory
 * @package MailMan\Transport
 */
class Factory
{
    /**
     * @var array Known transport types
     */
    protected static $classMap = [
        'file'      => 'Zend\Mail\Transport\File',
        'null'      => 'Zend\Mail\Transport\Null',
        'sendmail'  => 'Zend\Mail\Transport\Sendmail',
        'smtp'      => 'Zend\Mail\Transport\Smtp',
        'mandrill'  => 'MailMan\Transport\Mandrill\MandrillTransport'
    ];

    /**
     * @param array $spec
     * @return TransportInterface
     * @throws Exception\InvalidArgumentException
     * @throws Exception\DomainException
     */
    public static function create($spec = [])
    {
        if ($spec instanceof \Traversable) {
            $spec = ArrayUtils::iteratorToArray($spec);
        }

        if (!is_array($spec)) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects an array or Traversable argument; received "%s"',
                __METHOD__,
                (is_object($spec) ? get_class($spec) : gettype($spec))
            ));
        }

        if (!isset($spec['type'])) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects an array or Traversable argument with "type" field; "%s" does not contain a "type" field',
                __METHOD__,
                (is_object($spec) ? get_class($spec) : gettype($spec))
            ));
        }
        $type = $spec['type'];

        $normalizedType = strtolower($type);
        if (isset(static::$classMap[$normalizedType])) {
            $type = static::$classMap[$normalizedType];
        }

        if (!class_exists($type)) {
            throw new Exception\DomainException(sprintf(
                '%s expects the "type" attribute to resolve to an existing class; received "%s"',
                __METHOD__,
                $type
            ));
        }

        $transport = new $type;
        if (!$transport instanceof TransportInterface) {
            throw new Exception\DomainException(sprintf(
                '%s expects the "type" attribute to resolve to a valid %s instance; received "%s"',
                __METHOD__,
                'Zend\Mail\Transport\TransportInterface',
                $type
            ));
        }

        if (isset($spec['options'])) {
            $optClass = $type . 'Options';
            if (!class_exists($optClass)) {
                throw new Exception\DomainException(sprintf(
                    '%s expects the "options" attribute to resolve to an existing class; received "%s"',
                    __METHOD__,
                    $optClass
                ));
            }
            $opts = new $optClass($spec['options']);
            if (!$opts instanceof AbstractOptions) {
                throw new Exception\DomainException(sprintf(
                    '%s expects the "options" attribute to resolve to a valid %s instance; received "%s"',
                    __METHOD__,
                    'Zend\Stdlib\AbstractOptions',
                    $optClass
                ));
            }
            if (!method_exists($transport, 'setOptions')) {
                throw new Exception\DomainException(sprintf(
                    '%s expects the instance of %s class has a method named "%s" to setting options; method not found',
                    __METHOD__,
                    $type,
                    'setOptions'
                ));
            }
            $transport->setOptions($opts);
        }

        return $transport;
    }
} 