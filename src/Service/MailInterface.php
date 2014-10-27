<?php
namespace MailMan\Service;

use MailMan\Message;

/**
 * Interface MailInterface
 * @package MailMan\Service
 */
interface MailInterface
{
    /**
     * @param Message $message
     */
    public function send(Message $message);
}
