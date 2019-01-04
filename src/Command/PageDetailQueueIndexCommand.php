<?php

declare(strict_types=1);

namespace App\Command;

use App\Crawler\Crawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PageDetailQueueIndexCommand extends Command
{
    protected static $defaultName = 'page:detail:queue-index';

    /**
     * @var Crawler
     */
    private $crawler;

    public function __construct(Crawler $crawler)
    {
        parent::__construct();
        $this->crawler = $crawler;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Start crawling from the given page, related pages will be queued')
            ->addArgument('id', InputArgument::REQUIRED, 'The page-detail id')
            ->addOption('recursive', 'r', InputOption::VALUE_NONE, 'Recursive add all related pages to the queue')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $id = $input->getArgument('id');
        if (!\is_string($id)) {
            throw new \InvalidArgumentException('Invalid argument for page-detail ID');
        }

        $recursive = (bool) $input->getOption('recursive');

        $this->crawler->startCrawlingFromPageDetail($id, $recursive);
    }
}
