<?php

namespace OHMedia\EmailBundle\Service;

use Doctrine\ORM\EntityManager;
use OHMedia\EmailBundle\Entity\Email;
use OHMedia\SettingsBundle\Settings\Settings;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Twig\Environment as Twig;

class EmailHelper
{
    private $em;
    private $from;
    private $prefix;
    private $twig;
    private $stylesheet;
  
    public function __construct(EntityManager $em, Settings $settings, Twig $twig, string $stylesheet)
    {
        $this->em = $em;
        
        $fromEmail = $settings->get('ohmedia_email_from_email') ?: 'no-reply@ohmedia.ca';
        $fromName = $settings->get('ohmedia_email_from_name');
        $this->from = $fromName 
            ? sprintf('%s <%s>', $fromName, $fromEmail)
            : $fromEmail;
        
        $this->prefix = $settings->get('ohmedia_email_subject_prefix');
        
        $this->twig = $twig;
        
        $this->stylesheet = $stylesheet;
    }
    
    public function send(
        $subject,
        $template,
        array $parameters,
        $to,
        $cc = null,
        $bcc = null,
        $replyTo = null
    )
    {
        if ($this->prefix) {
            $subject = sprintf('%s %s', $this->prefix, $subject);
        }
        
        if (!isset($parameters['subject'])) {
            $parameters['subject'] = $subject;
        }
        
        $html = $this->getHtml($template, $parameters);
        
        $email = new Email();
        $email->setSubject($subject)
            ->setHtml($html)
            ->setTo($to)
            ->setCc($cc)
            ->setBcc($bcc)
            ->setReplyTo($replyTo)
            ->setFrom($this->from);
        
        $this->em->persist($email);
        $this->em->flush();
    }
    
    protected function getHtml($template, array $parameters)
    {
        $html = $this->twig->render($template, $parameters);
        
        if (file_exists($this->stylesheet)) {
            $cssToInlineStyles = new CssToInlineStyles();
            
            $css = file_get_contents($this->stylesheet);
            
            $html = $cssToInlineStyles->convert($html, $css);
        }
        
        return $html;
    }
}
