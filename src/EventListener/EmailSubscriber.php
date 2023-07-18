<?php

namespace OHMedia\EmailBundle\EventListener;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use OHMedia\EmailBundle\Entity\Email;
use OHMedia\EmailBundle\Util\EmailAddress;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MimeEmail;
use Twig\Environment as Twig;

class EmailSubscriber implements EventSubscriberInterface
{
    private $em;
    private $from;
    private $mailer;
    private $subjectPrefix;
    private $twig;

    public function __construct(
        EntityManager $em,
        MailerInterface $mailer,
        Twig $twig,
        string $fromEmail,
        string $fromName,
        ?string $subjectPrefix
    ) {
        $this->em = $em;
        $this->from = new EmailAddress($fromEmail, $fromName);
        $this->mailer = $mailer;
        $this->subjectPrefix = $subjectPrefix;
        $this->twig = $twig;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::postPersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $email = $args->getObject();

        if (!$email instanceof Email) {
            return;
        }

        $email
            ->setFrom($this->from)
            ->setCreatedAt(new \DateTime())
        ;

        if ($this->subjectPrefix) {
            $subject = $email->getSubject();
            $subject = sprintf('%s %s', $this->subjectPrefix, $subject);
            $email->setSubject($subject);
        }

        $template = $email->getTemplate();

        if ($template) {
            $html = $this->getHtml($template, $email->getParameters());

            $email->setHtml($html);
        }
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $email = $args->getObject();

        if (!$email instanceof Email) {
            return;
        }

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

    private function getHtml(string $template, array $parameters): string
    {
        $html = $this->twig->render('@OHMediaEmail/inline-css.html.twig', [
            'html' => $this->twig->render($template, $parameters),
        ]);

        return $html;
    }
}
