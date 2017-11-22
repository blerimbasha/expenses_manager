<?php

namespace AppBundle\Service;


class Mail
{
    private $adminEmail;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send($subject, $to, $template = 'default')
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('blerimi.v@gmail.com')
            ->setTo($to)
            ->setBody($template, 'text/html')
        ;
        $this->mailer->send($message);
    }
}
