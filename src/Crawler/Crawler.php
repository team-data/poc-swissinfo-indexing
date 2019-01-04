<?php

declare(strict_types=1);

namespace App\Crawler;

use App\Index\Searcher;
use App\Message\IndexPageDetail;
use App\Util\PageIDHelper;
use Liip\SwissinfoClient\Client;
use Liip\SwissinfoClient\Model\PageDetail;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class Crawler
{
    /**
     * @var Client
     */
    private $apiClient;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var Searcher
     */
    private $searcher;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Client $apiClient, MessageBusInterface $messageBus, Searcher $searcher, LoggerInterface $logger)
    {
        $this->apiClient = $apiClient;
        $this->messageBus = $messageBus;
        $this->logger = $logger;
        $this->searcher = $searcher;
    }

    public function startCrawlingFromPageDetail(string $id, bool $recursive = true): void
    {
        $this->enqueueDetailForIndexing($id, $recursive);

        if ($recursive) {
            $detail = $this->apiClient->getPageDetail($id);
            $this->queueIndexingFromPageDetail($detail);
        }
    }

    public function queueIndexingFromPageDetail(PageDetail $detail): void
    {
        foreach ($detail->getFooter()->getRelated() as $relatedPage) {
            $id = PageIDHelper::extractIdFromPageDetailUrl($relatedPage->getUrl());

            if (null === $id) {
                $this->logger->debug('Skipping queuing of related content, as no valid URL is available! page-id="{page-id}", url="{url}"', [
                    'page-id' => $relatedPage->getId(),
                    'url' => $relatedPage->getUrl(),
                ]);

                continue;
            }

            $this->enqueueDetailForIndexing($id, true);
        }
    }

    private function enqueueDetailForIndexing(string $id, bool $recursive): void
    {
        if ($this->searcher->existsPageDetail($id)) {
            $this->logger->notice('Skipping {id}, as it is indexed already', [
                'id' => $id,
            ]);

            return;
        }

        $this->logger->notice('Queuing page-detail for indexing "id={id}"', ['id' => $id]);
        $message = new IndexPageDetail($id, $recursive);
        $this->messageBus->dispatch($message);
    }
}
