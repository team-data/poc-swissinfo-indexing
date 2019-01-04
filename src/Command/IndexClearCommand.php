<?php

declare(strict_types=1);

namespace App\Command;

use App\Index\Indexer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class IndexClearCommand extends Command
{
    protected static $defaultName = 'index:clear';

    /**
     * @var Indexer
     */
    private $indexer;

    public function __construct(Indexer $indexer)
    {
        parent::__construct();
        $this->indexer = $indexer;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Clear the index')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Do it!')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $force = $input->getOption('force');

        if ($force) {
            $this->indexer->deleteAll();
        } else {
            $output->writeln('Use the --force!');
        }
    }
}
