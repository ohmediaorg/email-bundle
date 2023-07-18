<?php

namespace OHMedia\EmailBundle\Cleanup;

use OHMedia\CleanupBundle\Interfaces\CleanerInterface;
use OHMedia\EmailBundle\Repository\EmailRepository;
use Symfony\Component\Console\Output\OutputInterface;

class EmailCleaner implements CleanerInterface
{
    private $repo;
    private $cleanup;

    public function __construct(EmailRepository $repo, $cleanup)
    {
        $this->repo = $repo;
        $this->cleanup = $cleanup;
    }

    public function __invoke(OutputInterface $output): void
    {
        $since = new \DateTime($this->cleanup);

        $this->repo->deleteSentBefore($since);
    }
}
