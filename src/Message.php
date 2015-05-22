<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MailMan;

use MailMan\Exception;
use Zend\Mail\Message as ZendMailMessage;
use Zend\Mime\Message as ZendMimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part;

/**
 * Class Message
 */
class Message extends ZendMailMessage implements MessageInterface
{
    protected $encoding = 'UTF-8';

    /**
     * {@inheritdoc}
     */
    public function setBody($body)
    {
        if ($this->body instanceof ZendMimeMessage) {
            $headers = $this->getHeaders();
            $toClean = [];
            foreach ($headers as $header) {
                if (strpos(strtolower($header->getFieldName()), 'content') === 0) {
                    $toClean[] = $header->getFieldName();
                }
            }
            // NOTE: remove 'content' headers AFTER iterating them
            foreach ($toClean as $headerName) {
                $this->clearHeaderByName($headerName);
            }
        }
        return parent::setBody($body);
    }

    /**
     * {@inheritdoc}
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
                $attachment
            ));
        }

        $this->setBody($body);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addTextPart($content)
    {
        $body = $this->prepareBodyMessage();

        $textPart = new Part($content);
        $textPart->type = Mime::TYPE_TEXT;
        $body->addPart($textPart);

        $this->setBody($body);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addHtmlPart($content)
    {
        $body = $this->prepareBodyMessage();

        $textPart = new Part($content);
        $textPart->type = Mime::TYPE_HTML;
        $body->addPart($textPart);

        $this->setBody($body);
        return $this;
    }

    /**
     * @return ZendMimeMessage
     * @throws \MailMan\Exception\RuntimeException
     */
    protected function prepareBodyMessage()
    {
        $body = $this->getBody();

        if (null === $body) {
            $this->body = new ZendMimeMessage();
        }

        if (!$this->body instanceof ZendMimeMessage) {
            throw new Exception\RuntimeException(sprintf(
                'Body must be an instance of %s not %s',
                'Zend\Mime\Message',
                (is_object($this->body) ? get_class($this->body) : gettype($this->body))
            ));
        }
        return $this->body;
    }
}
