<?php

namespace MailModule\Service;

use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mime\Mime;
use Zend\Mime\Part;

/**
 * Class MailService
 *
 * @author Lorenzo Fontana <fontanalorenzo@me.com>
 */
class MailService
{

    /**
     * @var \Zend\Mail\Message
     */
    private $message;

    /**
     * @var \Zend\Mail\Transport\TransportInterface
     */
    private $transport;

    /**
     * @var array Attachments
     */
    private $attachments = [];

    /**
     * Ctor
     *
     * @param Message            $message
     * @param TransportInterface $transport
     */
    public function __construct(Message $message, TransportInterface $transport)
    {
        $this->message = $message;
        $this->transport = $transport;
    }

    /**
     * Send
     *
     * @return mixed
     */
    public function send()
    {
        $body = $this->getMessage()->getBody();
        if (count($this->attachments) > 0) {
            if (!$body instanceof \Zend\Mime\Message) {
                $body = new \Zend\Mime\Message();
                $bodyMessage = new \Zend\Mime\Part($body);
                $bodyMessage->type = Mime::TYPE_HTML;
                $body->addPart($bodyMessage);
            }

            foreach ($this->attachments as $attachment) {
                if (is_file($attachment) && is_readable($attachment)) {
                    $attachment = new Part(fopen($attachment, 'r'));
                    $attachment->type = 'application/pdf';
                    $attachment->encoding = Mime::ENCODING_BASE64;
                    $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;
                    $body->addPart($attachment);
                }
            }

            $this->setBody($body);
            $this->message->setEncoding('UTF-8');
        }

        return $this->transport->send($this->message);
    }

    /**
     * Get Message
     *
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Add Attachment
     *
     * @param string $attachment Attachment Path
     *
     * @return $this
     */
    public function addAttachment($attachment)
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    /**
     * Set Attachments
     *
     * @param array $attachments Array of attachment's paths
     *
     * @return $this
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * Return the attachments
     *
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Set body
     *
     * @param string|\Zend\Mime\Message $body
     *
     * @return $this
     */
    public function setBody($body)
    {
        switch (true) {
            case is_string($body) && preg_match("/<[^<]+>/", $body, $m) != 0:
                $bodyPart = new \Zend\Mime\Message();
                $bodyMessage = new \Zend\Mime\Part($body);
                $bodyMessage->type = Mime::TYPE_HTML;
                $bodyPart->addPart($bodyMessage);
                $this->message->setBody($bodyPart);
                $this->message->setEncoding('UTF-8');
                break;
            default:
                $this->message->setBody($body);
                break;
        }

        return $this;
    }
}
