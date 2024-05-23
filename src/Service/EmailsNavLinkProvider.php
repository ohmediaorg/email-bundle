<?php

namespace OHMedia\EmailBundle\Service;

use OHMedia\BackendBundle\Service\AbstractDeveloperOnlyNavLinkProvider;
use OHMedia\BootstrapBundle\Component\Nav\NavLink;
use OHMedia\EmailBundle\Entity\Email;
use OHMedia\EmailBundle\Security\Voter\EmailVoter;

class EmailsNavLinkProvider extends AbstractDeveloperOnlyNavLinkProvider
{
    public function getNavLink(): NavLink
    {
        return (new NavLink('Emails', 'email_index'))->setIcon('envelope-fill');
    }

    public function getVoterAttribute(): string
    {
        return EmailVoter::INDEX;
    }

    public function getVoterSubject(): mixed
    {
        return new Email();
    }
}
