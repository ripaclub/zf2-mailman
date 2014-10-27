<?php
namespace MailMan\Service;

use MailMan\Message;
use Zend\Mail\Transport\TransportInterface;

/**
 * Class MailService
 * @package MailMan\Service
 */
class MailService implements MailInterface
{
    /**
     * @var TransportInterface
     */
    protected $transport;

    /**
     * @var string
     */
    protected $defaultSender = null;

    /**
     * @var string
     */
    protected $defaultEncoding = null;

    /**
     * @param TransportInterface $transport
     * @param null $defaultSender
     * @param null $defaultEncoding
     */
    public function __construct(TransportInterface $transport, $defaultSender = null, $defaultEncoding = null)
    {
        $this->transport = $transport;
        $this->defaultSender = $defaultSender;
        $this->defaultEncoding = $defaultEncoding;
    }

    /**
     * @param Message $message
     */
    public function send(Message $message)
    {

    }
}
