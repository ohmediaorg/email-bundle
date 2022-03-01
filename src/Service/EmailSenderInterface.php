<?php

namespace JstnThms\EmailBundle\Service;

use JstnThms\EmailBundle\Entity\Email;

interface EmailSenderInterface
{
    public function send(Email $email);
}
