<?php

declare(strict_types=1);

namespace App\Index;

use Liip\SwissinfoClient\Model\PageDetail;
use Solarium\Client;
use Solarium\QueryType\Update\Query\Query as UpdateQuery;

class Indexer
{
    /**
     * @var Client
     */
    private $solrClient;

    public function __construct(Client $solrClient)
    {
        $this->solrClient = $solrClient;
    }

    public function indexPageDetail(PageDetail $detail, string $fullId): void
    {
        $query = new UpdateQuery();
        $query->addDocument(SolrPageDetailEntity::getSolrUpdateDocuments($detail, $fullId));
        $query->addCommit();
        $this->solrClient->execute($query);
    }

    public function deleteAll(): void
    {
        $query = new UpdateQuery();
        $query->addDeleteQuery('*:*');
        $query->addCommit();
        $this->solrClient->execute($query);
    }
}
