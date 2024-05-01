<?php

namespace OHMedia\EmailBundle\EventListener;

use Doctrine\ORM\Event\PostPersistEventArgs;
use OHMedia\EmailBundle\Entity\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MimeEmail;

class EmailPostPersist
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function postPersist(Email $email, PostPersistEventArgs $args): void
    {
        $mimeEmail = new MimeEmail();
        $mimeEmail
            ->from(...$email->getFrom())
            ->to(...$email->getTo())
            ->cc(...$email->getCc())
            ->bcc(...$email->getBcc())
            ->replyTo(...$email->getReplyTo())
            ->subject($email->getSubject())
            ->html($email->getHtml())
        ;

        foreach ($email->getAttachments() as $attachment) {
            $mimeEmail->attachFromPath(
                $attachment->getPath(),
                $attachment->getName(),
                $attachment->getContentType()
            );
        }

        $mimeEmail->getHeaders()
            // this header tells auto-repliers ("email holiday mode") to not
            // reply to this message because it's an automated email
            ->addTextHeader('X-Auto-Response-Suppress', 'OOF, DR, RN, NRN, AutoReply');

        $this->mailer->send($mimeEmail);
    }
}
