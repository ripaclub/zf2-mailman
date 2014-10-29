<?php
namespace MailMan;

/**
 * Interface MessageInterface
 */
interface MessageInterface
{
    /**
     * @param $attachment
     * @return self
     */
    public function addAttachment($attachment);

    /**
     * @param $content string
     * @return self
     */
    public function addTextPart($content);

    /**
     * @param $content string
     * @return self
     */
    public function addHtmlPart($content);
} 