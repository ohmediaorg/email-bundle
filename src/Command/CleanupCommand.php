<?php

namespace OHMedia\EmailBundle\Command;

use DateTime;
use Doctrine\ORM\EntityManager;
use OHMedia\EmailBundle\Entity\Email;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanupCommand extends Command
{
    private $em;
    private $cleanup;

    public function __construct(EntityManager $em, $cleanup)
    {
        $this->em = $em;
        $this->cleanup = $cleanup;

        parent::__construct();
    }
    
    protected function configure()
    {
        $this
            ->setName('ohmedia:email:cleanup')
            ->setDescription('Clean up old emails')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $since = new DateTime($this->cleanup);
        
        $this->em->getRepository(Email::class)->deleteSentBefore($since);
        
        return Command::SUCCESS;
    }
}
