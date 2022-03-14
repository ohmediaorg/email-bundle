<?php

namespace OHMedia\EmailBundle\Util;

use JsonSerializable;
use Stringable;

class EmailAddress implements JsonSerializable, Stringable
{
    private $email;
    private $name;

    public function __construct(string $email, string $name = '')
    {
        $this->email = $email;
        $this->name = $name;
    }

    public function jsonSerialize(): mixed
    {
        return (string) $this;
    }

    public function __toString(): string
    {
        if ($this->name) {
            return sprintf('%s <%s>', $this->name, $this->email);
        }

        return $this->email;
    }
}
