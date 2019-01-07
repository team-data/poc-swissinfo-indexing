<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Crawler\Crawler;
use App\Index\Indexer;
use App\Message\IndexPageDetail;
use Liip\SwissinfoClient\Client;
use Liip\SwissinfoClient\Exception\APIException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class IndexPageDetailHandler implements MessageHandlerInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Indexer
     */
    private $indexer;

    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Client $client, Indexer $indexer, Crawler $crawler, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->indexer = $indexer;
        $this->crawler = $crawler;
        $this->logger = $logger;
    }

    public function __invoke(IndexPageDetail $message): void
    {
        $this->logger->debug('Fetching page-detail "{id}"', ['id' => $message->getId()]);
        try {
            $detail = $this->client->getPageDetail($message->getId());
        } catch (APIException $exception) {
            throw $exception;
        }

        // Index the page detail
        $this->logger->notice('Indexing page {id}', [
            'id' => $message->getId(),
        ]);
        $this->indexer->indexPageDetail($detail, $message->getId());

        if ($message->isRecursiveIndexing()) {
            $this->crawler->queueIndexingFromPageDetail($detail);
        }
    }
}
