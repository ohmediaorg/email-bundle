<?php

namespace OHMedia\EmailBundle\Command;

use DateTime;
use Doctrine\ORM\EntityManager;
use Exception;
use OHMedia\EmailBundle\Entity\Email as EntityEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MimeEmail;

class SendCommand extends Command
{
    private $em;
    private $sender;

    public function __construct(EntityManager $em, MailerInterface $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('ohmedia:email:send')
            ->setDescription('Send unsent emails')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new DateTime();

        $entityEmails = $this->em->getRepository(Email::class)->getUnsent();

        foreach ($entityEmails as $entityEmail) {
            // flag the email as sending to prevent double-sending
            $entityEmail->setSending(true);
            $this->em->flush();

            try {
                $this->sendEmail($entityEmail);

                // sending was successful if we got here
                $email->setSentAt($now);
            }
            catch (Exception $e) {
                // need to catch the exception
                // but don't need to do anything with it
                echo $e->getMessage() . "\n";

                // TODO: set an error message on the $entityEmail ?
            }

            $entityEmail->setSending(false);
            $this->em->flush();
        }

        return Command::SUCCESS;
    }

    private function sendEmail(EntityEmail $entityEmail): void
    {
        $mimeEmail = new MimeEmail();
        $mimeEmail
            ->from(...$entityEmail->getFrom())
            ->to(...$entityEmail->getTo())
            ->cc(...$entityEmail->getCc())
            ->bcc(...$entityEmail->getBcc())
            ->replyTo(...$entityEmail->getReplyTo())
            ->subject($entityEmail->getSubject())
            ->html($entityEmail->getHtml())
        ;

        // TODO: need to actually add EmailAttachment functionality
        /*foreach ($entityEmail->getAttachments() as $attachment) {
            $mimeEmail->attachFromPath($attachment->getPath(), $attachment->getName());
        }*/

        $mimeEmail->getHeaders()
            // this header tells auto-repliers ("email holiday mode") to not
            // reply to this message because it's an automated email
            ->addTextHeader('X-Auto-Response-Suppress', 'OOF, DR, RN, NRN, AutoReply');

        $this->mailer->send($email);
    }
}
