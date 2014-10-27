<?php
namespace MailMan;

use MailMan\Exception;
use Zend\Mail\Message as ZendMailMessage;
use Zend\Mime\Message as ZendMimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part;

class Message extends ZendMailMessage implements MessageInterface
{
    protected $encoding = 'UTF-8';

    /**
     * Path of the file to attach
     *
     * @param $attachment string
     * @return $this
     */
    public function addAttachment($attachment)
    {
        $body = $this->prepareBodyMessage();

        $fileInfo = new \finfo(FILEINFO_MIME_TYPE);

        if (is_file($attachment) && is_readable($attachment)) {
                $pathInfo = pathinfo($attachment);
                $part = new Part(fopen($attachment, 'r'));
                $part->filename = $pathInfo['basename'];
                $part->type = $fileInfo->file($attachment);
                $part->encoding = Mime::ENCODING_BASE64;
                $part->disposition = Mime::DISPOSITION_ATTACHMENT;
                $body->addPart($part);
        } else {
           throw new Exception\InvalidArgumentException(sprintf(
               '%s expects a string path; received "%s"',
               __METHOD__,
               $attachment)
           );
        }

        return $this;
    }

    /**
     * @param $content string
     * @return self
     */
    public function addTextPart($content)
    {
        $body = $this->prepareBodyMessage();

        $textPart = new Part($content);
        $textPart->type = Mime::TYPE_HTML;
        $body->addPart($textPart);

        return $this;
    }

    /**
     * @param $content string
     * @return self
     */
    public function addHtmlPart($content)
    {
        $body = $this->prepareBodyMessage();

        $textPart = new Part($content);
        $textPart->type = Mime::TYPE_TEXT;
        $body->addPart($textPart);

        return $this;
    }

    /**
     * @return ZendMimeMessage
     */
    public function prepareBodyMessage()
    {
        $body = $this->getBody();

        if (null === $body) {
            $this->body = new ZendMimeMessage();
        }

        if ($this->body instanceof ZendMimeMessage) {
            throw new Exception\RuntimeException(sprintf(
                    'Body must be an instance of %s',
                    'Zend\Mime\Message')
            );
        }
        return $this->body;
    }

    /**
     * @param string $headerName
     * @param string $headerClass
     * @return \ArrayIterator|\Zend\Mail\Header\HeaderInterface
     */
    public function getHeaderByName($headerName, $headerClass)
    {
        return parent::getHeaderByName($headerName, $headerClass);
    }


}
