<?php

namespace MailModule\Mail\Transport;

use MailModule\Mail\Transport\MandrillOptions;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mail;
use \Mandrill as MandrillClient;

/**
 * Class Mandrill
 *
 * @author Lorenzo Fontana <fontanalorenzo@me.com>
 */
class Mandrill implements TransportInterface
{

    /**
     * @var MandrillOptions
     */
    protected $options;

    /**
     * Set Options
     *
     * @param MandrillOptions $options
     * @return $this
     */
    public function setOptions(MandrillOptions $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Send Mail Message
     *
     * @param Mail\Message $message
     * @return array
     */
    public function send(Mail\Message $message)
    {
        $mandrill = new MandrillClient($this->options->getApikey());

        $recipients = [];

        /** @var Mail\Address() $recipient */
        foreach ($message->getTo() as $recipient) {
            $recipients[] = [
                'email' => $recipient->getEmail(),
                'name' => $recipient->getName(),
                'type' => 'to'
            ];
        }

        $message = array(
            'html' => $message->getBody(),
            'text' => $message->getBodyText(),
            'subject' => $message->getSubject(),
            'from_email' => $message->getFrom()->current()->getEmail(),
            'from_name' => $message->getFrom()->current()->getName(),
            'to' => $recipients,
            'headers' => $message->getHeaders()->toArray(),
            'subaccount' => $this->options->getSubAccount(),
        );

        return $mandrill->messages->send($message);
    }
}
