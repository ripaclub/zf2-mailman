<?php
git \Mandrill;

use Mandrill;
use Zend\Mail;
use Zend\Mail\Transport\TransportInterface;

/**
 * Class MandrillTransport
 * @package MailMan\Transport\Mandrill
 */
class MandrillTransport implements TransportInterface
{
    protected $mandrillClient = null;

    /**
     * Send a mail message
     *
     * @param  Mail\Message $message
     */
    public function send(Mail\Message $message)
    {
        if ($this->hasClient()) {
            $this->createMandrillClient();
        }
    }

    /**
     *
     */
    protected function createMandrillClient()
    {
        $this->mandrillClient = new Mandrill($this->options->getApikey());
    }

    /**
     * @return bool
     */
    protected function hasClient()
    {
        if ($this->mandrillClient) {
            return true;
        }
        return false;
    }

}
