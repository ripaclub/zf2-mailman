<?php
/**
 * Created by PhpStorm.
 * User: visa
 * Date: 08/07/15
 * Time: 15.28
 */

namespace MailMan\Service;


trait MailServiceAwareTrait
{
    protected $mailService;
    /**
     * @return null|MailInterface
     */
    public function getMailService()
    {
        return $this->mailService;
    }

    /**
     * @param MailInterface $mailService
     * @return $this
     */
    public function setMailService(MailInterface $mailService)
    {
        $this->mailService = $mailService;
        return $this;
    }


}