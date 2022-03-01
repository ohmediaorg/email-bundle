<?php

namespace JstnThms\EmailBundle\Service;

use Exception;
use JstnThms\EmailBundle\Entity\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email as MimeEmail;
use Swift_Message;

class EmailSender implements EmailSenderInterface
{
    private $mailer;
    
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    
    public function send(Email $email)
    {
        $mimeEmail = new MimeEmail();
        
        $mimeEmail
            ->subject($email->getSubject())
            ->html($email->getHtml(), 'text/html');
        
        $to = $email->getTo();
        
        foreach ($to as $t) {
            $mimeEmail->addTo($t);
        }
        
        $from = $email->getFrom();
        
        foreach ($from as $f) {
            $mimeEmail->addFrom($f);
        }
        
        if ($cc = $email->getCc()) {
            foreach ($cc as $c) {
                $mimeEmail->addCc($c);
            }
        }
        
        if ($bcc = $email->getBcc()) {
            foreach ($bcc as $b) {
                $mimeEmail->addBcc($b);
            }
        }
        
        if ($replyTo = $email->getReplyTo()) {
            foreach ($replyTo as $r) {
                $mimeEmail->addReplyTo($r);
            }
        }
        
        $this->mailer->send($mimeEmail);
    }
}
