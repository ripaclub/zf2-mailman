<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MailMan;

/**
 * Interface MessageInterface
 */
interface MessageInterface
{
    /**
     * Path of the file to attach to the message
     *
     * @param $attachment
     * @return self
     */
    public function addAttachment($attachment);

    /**
     * Text content to add to the message
     *
     * @param $content string
     * @return self
     */
    public function addTextPart($content);

    /**
     * Content to add as HTML part to the message
     *
     * @param $content string
     * @return self
     */
    public function addHtmlPart($content);
}
