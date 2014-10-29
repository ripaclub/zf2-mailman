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
