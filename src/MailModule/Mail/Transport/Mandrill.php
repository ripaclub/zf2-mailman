<?php

namespace MailModule\Mail\Transport;

use Zend\Mail\Transport\TransportInterface;
use Zend\Mail;
use \Mandrill as MandrillClient;
use Zend\Mime\Message;

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

        $messageBody = $message->getBody();
        switch (true) {
            case $messageBody instanceof Message:
                /** @var \Zend\Mime\Message $messageBody */
                $body = $messageBody->generateMessage();
                break;
            case is_string($messageBody):
                $body = $messageBody;
                break;
            default:
                $body = (string)$messageBody;
                break;
        }

        $message = array(
            'html' => $body,
            'text' => $message->getBodyText(),
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
        );

        return $mandrill->messages->send($message);
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
