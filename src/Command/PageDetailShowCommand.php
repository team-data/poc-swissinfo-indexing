<?php

declare(strict_types=1);

namespace App\Command;

use Liip\SwissinfoClient\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PageDetailShowCommand extends Command
{
    protected static $defaultName = 'page:detail:show';

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
            ->setDescription('Fetch the given page-detail from the API')
            ->addArgument('id', InputArgument::REQUIRED, 'The page-detail id. Example: "42579868/40001118"')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $id = $input->getArgument('id');
        if (!\is_string($id)) {
            throw new \InvalidArgumentException('Invalid argument for page-detail ID');
        }

        $pageDetail = $this->apiClient->getPageDetail($id);

        dump($pageDetail);
    }
}
