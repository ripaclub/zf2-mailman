<?php
/**
 * Created by PhpStorm.
 * User: visa
 * Date: 08/07/15
 * Time: 15.27
 */

namespace MailMan\Service;


interface MailServiceAwareInterface
{
    /**
     * @return null|MailInterface
     */
    public function getMailService();

    /**
     * @param MailInterface $mailService
     * @return $this
     */
    public function setMailService(MailInterface $mailService);
}