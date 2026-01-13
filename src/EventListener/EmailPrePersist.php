<?php

namespace OHMedia\EmailBundle\EventListener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use OHMedia\EmailBundle\Entity\Email;
use OHMedia\EmailBundle\Util\EmailAddress;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Environment as Twig;

class EmailPrePersist
{
    private EmailAddress $from;

    public function __construct(
        private Twig $twig,
        #[Autowire('%oh_media_email.from_email%')]
        string $fromEmail,
        #[Autowire('%oh_media_email.from_name%')]
        string $fromName,
        #[Autowire('%oh_media_email.subject_prefix%')]
        private ?string $subjectPrefix,
        #[Autowire('%oh_media_email.header_background%')]
        private string $headerBackground,
        #[Autowire('%oh_media_email.link_color%')]
        private string $linkColor,
    ) {
        $this->from = new EmailAddress($fromEmail, $fromName);
    }

    public function prePersist(Email $email, PrePersistEventArgs $args): void
    {
        $email
            ->setFrom($this->from)
            ->setCreatedAt(new \DateTimeImmutable())
        ;

        if ($this->subjectPrefix) {
            $subject = $email->getSubject();
            $subject = sprintf('%s %s', $this->subjectPrefix, $subject);
            $email->setSubject($subject);
        }

        $template = $email->getTemplate();

        if ($template) {
            $parameters = $email->getParameters();

            $parameters['_subject'] = $email->getSubject();

            $html = $this->getHtml($template, $parameters);

            $email->setHtml($html);
        }
    }

    private function getHtml(string $template, array $parameters): string
    {
        $html = $this->twig->render('@OHMediaEmail/inline-css.html.twig', [
            'html' => $this->twig->render($template, $parameters),
            'header_background' => $this->headerBackground,
            'link_color' => $this->linkColor,
        ]);

        return $html;
    }
}
