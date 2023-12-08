<?php

namespace OHMedia\EmailBundle\EventListener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use OHMedia\EmailBundle\Entity\Email;
use OHMedia\EmailBundle\Util\EmailAddress;
use Twig\Environment as Twig;

class EmailPrePersist
{
    private $from;
    private $subjectPrefix;
    private $twig;

    public function __construct(
        Twig $twig,
        string $fromEmail,
        string $fromName,
        ?string $subjectPrefix
    ) {
        $this->from = new EmailAddress($fromEmail, $fromName);
        $this->subjectPrefix = $subjectPrefix;
        $this->twig = $twig;
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
        ]);

        return $html;
    }
}
