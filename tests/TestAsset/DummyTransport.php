<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MailManTest\TestAsset;

use Zend\Mail\Transport\TransportInterface;
use Zend\Mail\Message;

/**
 * Class DumyTransport
 */
class DummyTransport implements TransportInterface
{
    /**
     * {@inheritdoc}
     */
    public function send(Message $message)
    {
        return;
    }
}
