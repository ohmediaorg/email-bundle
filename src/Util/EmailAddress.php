<?php

namespace JstnThms\EmailBundle\Util;

class EmailAddress
{
    private $email;
    private $name;
    
    public function __construct(string $email, string $name = '')
    {
        $this->email = $email;
        $this->name = $name;
    }
    
    public function __toString()
    {
        if ($this->name) {
            return sprintf('%s <%s>', $this->name, $this->email);
        }
        
        return $this->email;
    }
}
