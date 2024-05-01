<?php

namespace OHMedia\EmailBundle\Cleanup;

use OHMedia\CleanupBundle\Interfaces\CleanerInterface;
use OHMedia\EmailBundle\Repository\EmailRepository;
use Symfony\Component\Console\Output\OutputInterface;

class EmailCleaner implements CleanerInterface
{
    public function __construct(
        private EmailRepository $repo,
        private string $cleanup
    ) {
    }

    public function __invoke(OutputInterface $output): void
    {
        $since = new \DateTime($this->cleanup);

        $this->repo->deleteSentBefore($since);
    }
}
