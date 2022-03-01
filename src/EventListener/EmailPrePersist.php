<?php

namespace OHMedia\EmailBundle\EventListener;

use Doctrine\ORM\EntityManager;
use OHMedia\EmailBundle\Entity\Email;
use OHMedia\EmailBundle\Util\EmailAddress;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Twig\Environment as Twig;

class EmailPrePersist
{
    private $em;
    private $from;
    private $subjectPrefix;
    private $twig;

    public function __construct(
        EntityManager $em,
        Twig $twig,
        EmailAddress $from,
        ?string $subjectPrefix
    )
    {
        $this->em = $em;

        $this->from = $from;

        $this->subjectPrefix = $subjectPrefix;

        $this->twig = $twig;
    }

    public function prePersist(Email $email, LifecycleEventArgs $event): void
    {
        $email->setFrom($this->from);

        if ($this->subjectPrefix) {
            $subject = $email->getSubject();
            $subject = sprintf('%s %s', $this->subjectPrefix, $subject);
            $email->setSubject($subject);
        }

        $template = $email->getTemplate();
        $parameters = $email->getParameters();

        if ($template) {
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
