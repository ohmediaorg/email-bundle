<?php

namespace OHMedia\EmailBundle\Service;

use OHMedia\EmailBundle\Entity\Email;

interface EmailSenderInterface
{
    public function send(Email $email);
}
