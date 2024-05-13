<?php

namespace OHMedia\EmailBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OHMedia\EmailBundle\Repository\EmailRepository;
use OHMedia\EmailBundle\Util\EmailAddress;
use OHMedia\EmailBundle\Util\EmailAttachment;

#[ORM\Entity(repositoryClass: EmailRepository::class)]
class Email
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $html = null;

    #[ORM\Column(name: 'recipients')]
    private ?array $to = [];

    #[ORM\Column(nullable: true)]
    private ?array $cc = [];

    #[ORM\Column(nullable: true)]
    private ?array $bcc = [];

    #[ORM\Column(name: 'senders', nullable: true)]
    private ?array $from = [];

    #[ORM\Column(nullable: true)]
    private ?array $reply_to = [];

    #[ORM\Column(nullable: true)]
    private ?array $attachments = [];

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(string $html): static
    {
        $this->html = $html;

        return $this;
    }

    public function getTo(): array
    {
        return $this->emailAddressesToStrings($this->to);
    }

    public function setTo(EmailAddress ...$to): static
    {
        $this->to = $to;

        return $this;
    }

    public function getCc(): array
    {
        return $this->emailAddressesToStrings($this->cc);
    }

    public function setCc(EmailAddress ...$cc): static
    {
        $this->cc = $cc;

        return $this;
    }

    public function getBcc(): array
    {
        return $this->emailAddressesToStrings($this->bcc);
    }

    public function setBcc(EmailAddress ...$bcc): static
    {
        $this->bcc = $bcc;

        return $this;
    }

    public function getFrom(): array
    {
        return $this->emailAddressesToStrings($this->from);
    }

    public function setFrom(EmailAddress ...$from): static
    {
        $this->from = $from;

        return $this;
    }

    public function getReplyTo(): array
    {
        return $this->emailAddressesToStrings($this->reply_to);
    }

    public function setReplyTo(EmailAddress ...$replyTo): static
    {
        $this->reply_to = $replyTo;

        return $this;
    }

    public function getAttachments(): array
    {
        $attachments = [];

        foreach ($this->attachments as $attachment) {
            $attachments[] = new EmailAttachment(
                $attachment['path'],
                $attachment['name'],
                $attachment['contentType']
            );
        }

        return $attachments;
    }

    public function setAttachments(EmailAttachment ...$attachments): static
    {
        $this->attachments = $attachments;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): static
    {
        $this->created_at = $createdAt;

        return $this;
    }

    private $template;
    private $parameters;

    public function setTemplate(string $template, array $parameters = [])
    {
        $this->template = $template;
        $this->parameters = $parameters;

        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    private function emailAddressesToStrings(array $emails)
    {
        return array_map(function ($email) {
            return (string) $email;
        }, $emails);
    }
}
