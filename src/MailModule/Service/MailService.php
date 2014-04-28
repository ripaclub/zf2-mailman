<?php

namespace MailModule\Service;

use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\View\Renderer\RendererInterface;

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
     * @var \Zend\View\Renderer\RendererInterface
     */
    private $renderer;

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
     * @param Message $message
     * @param RendererInterface $renderer
     * @param TransportInterface $transport
     */
    public function __construct(Message $message, RendererInterface $renderer, TransportInterface $transport)
    {
        $this->message = $message;
        $this->renderer = $renderer;
        $this->transport = $transport;
    }

    /**
     * Send
     *
     * @return mixed
     */
    public function send()
    {
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
     * @return $this
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }
}
