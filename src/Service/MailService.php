<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MailMan\Service;

use MailMan\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mime;

/**
 * Class MailService
 *
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
     * @var array
     */
    protected $additionalInfo = [];

    /**
     * @param TransportInterface $transport
     * @param null $defaultSender
     */
    public function __construct(TransportInterface $transport, $defaultSender = null)
    {
        $this->transport = $transport;
        $this->defaultSender = $defaultSender;
    }

    /**
     * @param Message $message
     */
    public function send(Message $message)
    {
        $this->checkFrom($message);
        return $this->transport->send($message);
    }

    /**
     * @param Message $message
     * @return $this
     */
    protected function checkFrom(Message $message)
    {
        if ($this->defaultSender) {
            if ($message->getFrom()->count() == 0) {
                $message->setFrom($this->defaultSender);
            }
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
    }

    /**
     * @param array $additionalInfo
     * @return self
     */
    public function setAdditionalInfo(array $additionalInfo)
    {
        $this->additionalInfo = $additionalInfo;
        return $this;
    }
}
