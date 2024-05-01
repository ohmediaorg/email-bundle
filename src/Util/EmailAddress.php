<?php

namespace OHMedia\EmailBundle\Util;

class EmailAddress implements \JsonSerializable, \Stringable
{
    public function __construct(
        private string $email,
        private string $name = ''
    ) {
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
