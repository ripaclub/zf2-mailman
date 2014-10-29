<?php
namespace MailMan\Service;

use MailMan\Message;
use Zend\Mime;
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
        $this->prepareMessage($message)
            ->checkFrom($message);

        return $this->transport->send($message);
    }

    /**
     * Prepare header message
     *
     * @param Message $message
     * @return self
     */
    protected function prepareMessage(Message $message)
    {
        $body = $message->getBody();

        if ($body instanceof Mime\Message) {
            /* @var \Zend\Mime\Message $body */

            $headers = $message->getHeaders();
            $message->getHeaderByName('mime-version', 'Zend\Mail\Header\MimeVersion');

            if ($body->isMultiPart()) {
                $mime   = $body->getMime();
                $header = $message->getHeaderByName('content-type', 'Zend\Mail\Header\ContentType');
                $header->setType('multipart/mixed');
                $header->addParameter('boundary', $mime->boundary());
            }

            $parts = $body->getParts();
            if (!empty($parts)) {
                $part = array_shift($parts);
                $headers->addHeaders($part->getHeadersArray());
            }
        }
        return $this;
    }

    /**
     * @param Message $message
     * @return $this
     */
    public function checkFrom(Message $message)
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
