<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MailMan\Transport\Mandrill;

use Mandrill as ClientMandrill;
use Zend\Mime\Message;
use Zend\Mail;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mime\Mime;
use MailMan\Exception;

/**
 * Class Mandrill
 *
 * @package MailMan\Transport\Mandrill
 */
class Mandrill implements TransportInterface
{
    /**
     * @var null|ClientMandrill
     */
    protected $mandrillClient = null;

    /**
     * @var MandrillOptions
     */
    protected $options = null;

    /**
     * Send a mail message
     *
     * @param Mail\Message $message
     * @return array
     */
    public function send(Mail\Message $message)
    {
        $this->getMandrillClient();

        $body = $message->getBody();
        $attachments = [];

        switch (true) {
            case $body instanceof Message:
                $bodyHtml = $this->getHtmlPart($body);
                $bodyText = $this->getTextPart($body);
                $attachments = $this->getAttachments($body);
                break;
            case is_string($body):
                $bodyHtml = $body;
                $bodyText = $message->getBodyText();
                break;
            case is_object($body):
                $bodyHtml = $body->__toString();
                $bodyText = $message->getBodyText();
                break;
            default:
                throw new Exception\InvalidArgumentException(sprintf(
                    '"%s" expectes a body that is a string, an object or a Zend\Mime\Message; received "%s"',
                    __METHOD__,
                    is_object($body) ? get_class($body) : gettype($body)
                ));
                break;
        }

        $message = [
            'html' => $bodyHtml,
            'text' => $bodyText,
            'subject' => $message->getSubject(),
            'from_email' => $message->getFrom()->current()->getEmail(),
            'from_name' => $message->getFrom()->current()->getName(),
            'to' => array_merge(
                $this->mapAddressListToArray($message->getTo(), 'to'),
                $this->mapAddressListToArray($message->getCc(), 'cc'),
                $this->mapAddressListToArray($message->getBcc(), 'bcc')
            ),
            'headers' => $message->getHeaders()->toArray(),
            'subaccount' => $this->options->getSubAccount(),
            'attachments' => $attachments
        ];

        return $this->mandrillClient->messages->send($message);
    }

    /**
     * @param MandrillOptions $options
     * @return self
     */
    public function setOptions(MandrillOptions $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return Mandrill
     */
    public function getMandrillClient()
    {
        if (!$this->mandrillClient) {
            $this->mandrillClient = new ClientMandrill($this->options->getApikey());
        }
        return $this->mandrillClient;
    }

    /**
     * @param Message $mimeMessage
     * @return string
     */
    protected function getTextPart(Message $mimeMessage)
    {
        $content = '';
        $parts = $mimeMessage->getParts();
        /* @var $part \Zend\Mime\Part */
        foreach ($parts as $part) {
            if ($part->type == Mime::TYPE_TEXT) {
                $content = $part->getContent();
            }
        }
        return $content;
    }

    /**
     * @param Message $mimeMessage
     * @return string
     */
    protected function getHtmlPart(Message $mimeMessage)
    {
        $content = '';
        $parts = $mimeMessage->getParts();
        /* @var $part \Zend\Mime\Part */
        foreach ($parts as $part) {
            if ($part->type == Mime::TYPE_HTML) {
                $content = $part->getContent();
            }
        }
        return $content;
    }

    /**
     * @param Message $mimeMessage
     * @return array
     */
    protected function getAttachments(Message $mimeMessage)
    {
        $attachments = [];
        $parts = $mimeMessage->getParts();
        /** @var \Zend\Mime\Part $part */
        foreach ($parts as $part) {

            if (!isset($part->filename)) {
                continue;
            }

            $attachments[] = [
                'content' => $part->getContent(),
                'type' => $part->type,
                'name' => $part->filename,
            ];
        }
        return $attachments;
    }

    /**
     * Map Address List to Mandrill array
     *
     * @param Mail\AddressList $addresses
     * @param string $type
     * @return array
     */
    protected function mapAddressListToArray(Mail\AddressList $addresses, $type = 'to')
    {
        $array = [];
        /** @var Mail\Address() $address */
        foreach ($addresses as $address) {
            $array[] = [
                'email' => $address->getEmail(),
                'name' => $address->getName(),
                'type' => $type
            ];
        }
        return $array;
    }
}
