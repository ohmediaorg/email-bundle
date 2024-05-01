<?php

namespace OHMedia\EmailBundle\EventListener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use OHMedia\EmailBundle\Entity\Email;
use OHMedia\EmailBundle\Util\EmailAddress;
use Twig\Environment as Twig;

class EmailPrePersist
{
    private EmailAddress $from;

    public function __construct(
        private Twig $twig,
        string $fromEmail,
        string $fromName,
        private ?string $subjectPrefix,
        private string $headerBackground,
        private string $linkColor
    ) {
        $this->from = new EmailAddress($fromEmail, $fromName);
    }

    public function prePersist(Email $email, PrePersistEventArgs $args): void
    {
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
