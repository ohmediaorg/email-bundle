<?php

namespace OHMedia\EmailBundle\Service;

use OHMedia\BackendBundle\Service\AbstractNavItemProvider;
use OHMedia\BootstrapBundle\Component\Nav\NavItemInterface;
use OHMedia\BootstrapBundle\Component\Nav\NavLink;
use OHMedia\EmailBundle\Entity\Email;
use OHMedia\EmailBundle\Security\Voter\EmailVoter;

class EmailNavItemProvider extends AbstractNavItemProvider
{
    public function getNavItem(): ?NavItemInterface
    {
        if ($this->isGranted(EmailVoter::INDEX, new Email())) {
            return (new NavLink('Emails', 'email_index'))
                ->setIcon('envelope-fill');
        }

        return null;
    }
}
