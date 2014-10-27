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
        $message = $this->prepareMessage($message);
        $message->setFrom('visa');
        $message->setTo('antonino.visalli@gmail.com');
        $message->addHtmlPart('<strong>FUNZIONA</strong>>');
        $message->addTextPart('FUNZIONA');
        return $this->transport->send($message);
    }

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
        return $message;
    }
}
