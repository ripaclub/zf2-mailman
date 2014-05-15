<?php

namespace MailModule\Mail\Transport;

use Zend\Mail\Transport\TransportInterface;
use Zend\Mail;
use \Mandrill as MandrillClient;
use Zend\Mime\Message;
use Zend\Mime\Mime;
use Zend\Mime\Part;

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
     *
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
     *
     * @return array
     */
    public function send(Mail\Message $message)
    {
        $mandrill = new MandrillClient($this->options->getApikey());
        $messageBody = $message->getBody();
        $attachments = [];
        switch (true) {
            case $messageBody instanceof Message:
                /** @var \Zend\Mime\Message $messageBodyCopy */
                $messageBodyCopy = clone $messageBody;
                $parts = [];
                /** @var \Zend\Mime\Part $part */
                foreach ($messageBodyCopy->getParts() as $part) {
                    if (isset($part->filename)) {
                        continue;
                    }
                    $parts[] = $part;
                }

                $messageBodyCopy->setParts($parts);

                $parts = [];
                foreach ($messageBody->getParts() as $part) {
                    $parts[] = $part;
                }

                $messageBody->setParts($parts);

                $attachments = $this->attachmentsFromMessageBody($messageBody);

                $body = $messageBodyCopy->generateMessage();
                $bodyText = $message->getBodyText();


                break;
            case is_string($messageBody):
                $body = $messageBody;
                $bodyText = $message->getBodyText();
                break;
            default:
                $body = (string)$messageBody;
                $bodyText = $message->getBodyText();
                break;
        }


        $message = array(
            'html'        => $body,
            'text'        => $bodyText,
            'subject'     => $message->getSubject(),
            'from_email'  => $message->getFrom()->current()->getEmail(),
            'from_name'   => $message->getFrom()->current()->getName(),
            'to'          => array_merge(
                $this->mapAddressListToArray($message->getTo(), 'to'),
                $this->mapAddressListToArray($message->getCc(), 'cc'),
                $this->mapAddressListToArray($message->getBcc(), 'bcc')
            ),
            'headers'     => $message->getHeaders()->toArray(),
            'subaccount'  => $this->options->getSubAccount(),
            'attachments' => $attachments
        );

        return $mandrill->messages->send($message);
    }

    /**
     * Map Address List to Mandrill array
     *
     * @param Mail\AddressList $addresses
     * @param string           $type
     *
     * @return array
     */
    protected function mapAddressListToArray(Mail\AddressList $addresses, $type = 'to')
    {
        $array = [];
        /** @var Mail\Address() $address */
        foreach ($addresses as $address) {
            $array[] = [
                'email' => $address->getEmail(),
                'name'  => $address->getName(),
                'type'  => $type
            ];
        }
        return $array;
    }

    /**
     * Returns all attachments in the message body
     *
     * @param Message $body
     *
     * @return array
     */
    protected function attachmentsFromMessageBody(Message $body)
    {
        $attachments = [];
        /** @var \Zend\Mime\Part $part */
        foreach ($body->getParts() as $part) {

            if (!isset($part->filename)) {
                continue;
            }

            $attachments[] = [
                'content' => $part->getContent(),
                'type'    => $part->type,
                'name'    => $part->filename,
            ];
        }
        return $attachments;

    }
}
