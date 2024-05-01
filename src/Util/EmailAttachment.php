<?php

namespace OHMedia\EmailBundle\Util;

class EmailAttachment implements \JsonSerializable
{
    public function __construct(
        private string $path,
        private ?string $name = null,
        private ?string $contentType = null
    ) {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function jsonSerialize(): mixed
    {
        return [
           'path' => $this->path,
           'name' => $this->name,
           'contentType' => $this->contentType,
        ];
    }
}
