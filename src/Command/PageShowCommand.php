<?php

declare(strict_types=1);

namespace App\Command;

use Liip\SwissinfoClient\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PageShowCommand extends Command
{
    protected static $defaultName = 'page:show';

    /**
     * @var Client
     */
    private $apiClient;

    public function __construct(Client $apiClient)
    {
        parent::__construct();
        $this->apiClient = $apiClient;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Fetch the given page from the API')
            ->addArgument('page-id', InputArgument::REQUIRED, 'The page id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $pageId = $input->getArgument('page-id');
        if (!is_string($pageId)) {
            throw new \InvalidArgumentException('Invalid argument for page-id');
        }

        $pageDetail = $this->apiClient->getPageDetail($pageId);

        dump($pageDetail);
    }
}
