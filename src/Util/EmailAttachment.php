<?php

namespace OHMedia\EmailBundle\Util;

use JsonSerializable;

class EmailAttachment implements JsonSerializable
{
    private $path;
    private $name;
    private $mime;

    public function __construct(
        string $path,
        string $name = null,
        string $contentType = null
    )
    {
        $this->path = $path;
        $this->name = $name;
        $this->contentType = $contentType;
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
