<?php

namespace OHMedia\EmailBundle\Entity;

use OHMedia\EmailBundle\Repository\EmailRepository;
use OHMedia\EmailBundle\Util\EmailAddress;
use OHMedia\EmailBundle\Util\EmailAttachment;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmailRepository::class)]
class Email
{
    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $subject;

    #[ORM\Column(type: 'text')]
    private $html;

    #[ORM\Column(type: 'json', name: 'recipients')]
    private $to = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private $cc = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private $bcc = [];

    #[ORM\Column(type: 'json', name: 'senders', nullable: true)]
    private $from = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private $reply_to = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private $attachments = [];

    #[ORM\Column(type: 'datetime')]
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function getTo(): array
    {
        return $this->emailAddressesToStrings($this->to);
    }

    public function setTo(EmailAddress ...$to): self
    {
        $this->to = $to;

        return $this;
    }

    public function getCc(): array
    {
        return $this->emailAddressesToStrings($this->cc);
    }

    public function setCc(EmailAddress ...$cc): self
    {
        $this->cc = $cc;

        return $this;
    }

    public function getBcc(): array
    {
        return $this->emailAddressesToStrings($this->bcc);
    }

    public function setBcc(EmailAddress ...$bcc): self
    {
        $this->bcc = $bcc;

        return $this;
    }

    public function getFrom(): array
    {
        return $this->emailAddressesToStrings($this->from);
    }

    public function setFrom(EmailAddress ...$from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getReplyTo(): array
    {
        return $this->emailAddressesToStrings($this->reply_to);
    }

    public function setReplyTo(EmailAddress ...$replyTo): self
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

    public function setAttachments(EmailAttachment ...$attachments): self
    {
        $this->attachments = $attachments;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
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
