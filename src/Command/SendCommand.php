<?php

namespace OHMedia\EmailBundle\Command;

use DateTime;
use Doctrine\ORM\EntityManager;
use Exception;
use OHMedia\EmailBundle\Entity\Email;
use OHMedia\EmailBundle\Service\EmailSender;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendCommand extends Command
{
    private $em;
    private $sender;

    public function __construct(EntityManager $em, EmailSender $sender)
    {
        $this->em = $em;
        $this->sender = $sender;

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

        $emails = $this->em->getRepository(Email::class)->getUnsent();

        foreach ($emails as $email) {
            // flag the email as sending to prevent double-sending
            $email->setSending(true);
            $this->em->flush();

            try {
                $this->sender->send($email);

                // sending was successful if we got here
                $email->setSentAt($now);
            }
            catch (Exception $e) {
                // need to catch the exception
                // but don't need to do anything with it
                echo $e->getMessage() . "\n";
            }

            $email->setSending(false);
            $this->em->flush();
        }

        return Command::SUCCESS;
    }
}
