<?php

namespace OHMedia\EmailBundle\Entity;

use OHMedia\EmailBundle\Repository\EmailRepository;
use OHMedia\EmailBundle\Util\EmailAddress;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmailRepository::class)
 * @ORM\Table(name="emails")
 */
class Email
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     */
    private $html;

    /**
     * @ORM\Column(type="json", name="recipients")
     */
    private $to = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $cc = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $bcc = [];

    /**
     * @ORM\Column(type="json", name="senders", nullable=true)
     */
    private $from = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $reply_to = [];

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sending;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $sent_at;

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
        return $this->to;
    }

    public function setTo(EmailAddress ...$to): self
    {
        $this->to = $to;

        return $this;
    }

    public function getCc(): array
    {
        return $this->cc;
    }

    public function setCc(EmailAddress ...$cc): self
    {
        $this->cc = $cc;

        return $this;
    }

    public function getBcc(): array
    {
        return $this->bcc;
    }

    public function setBcc(EmailAddress ...$bcc): self
    {
        $this->bcc = $bcc;

        return $this;
    }

    public function getFrom(): array
    {
        return $this->from;
    }

    public function setFrom(EmailAddress ...$from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getReplyTo(): array
    {
        return $this->replyTo;
    }

    public function setReplyTo(EmailAddress ...$replyTo): self
    {
        $this->reply_to = $replyTo;

        return $this;
    }

    public function getSending(): ?bool
    {
        return $this->sending;
    }

    public function setSending(?bool $sending): self
    {
        $this->sending = $sending;

        return $this;
    }

    public function getSentAt(): ?DateTimeInterface
    {
        return $this->sent_at;
    }

    public function setSentAt(?DateTimeInterface $sentAt): self
    {
        $this->sent_at = $sentAt;

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
}
