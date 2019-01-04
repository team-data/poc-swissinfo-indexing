<?php

declare(strict_types=1);

namespace App\Index;

use App\Model\SearchResultList;
use Solarium\Client;
use Solarium\QueryType\Select\Query\FilterQuery;
use Solarium\QueryType\Select\Query\Query as SelectQuery;
use Solarium\QueryType\Select\Result\Document as SelectResultDocument;
use Solarium\QueryType\Select\Result\Result as SelectResult;

class Searcher
{
    /**
     * @var Client
     */
    private $solrClient;

    public function __construct(Client $solrClient)
    {
        $this->solrClient = $solrClient;
    }

    /**
     * Check if the given detail-page is in the index.
     */
    public function existsPageDetail(string $id): bool
    {
        $query = new SelectQuery();
        $query->setRows(1);

        $filterQuery = new FilterQuery();
        $filterQuery
            ->setKey('id-filter')
            ->setQuery(sprintf('id:%s', $id))
        ;

        $query->addFilterQuery($filterQuery);

        /** @var SelectResult $res */
        $res = $this->solrClient->execute($query);

        return 1 === $res->getNumFound();
    }

    public function search(string $queryString): SearchResultList
    {
        $searchResultList = new SearchResultList();

        $query = new SelectQuery();
        $query->setQuery($queryString);

        if ('' === $queryString) {
            $query->addSort('date_s', 'DESC');
        }

        $dismax = $query->getEDisMax();
        $dismax->setQueryAlternative('*:*');
        $dismax->setQueryFields(implode(' ', [
            'contents_txt',
            'title_txt^2',
        ]));

        /** @var SelectResult $result */
        $result = $this->solrClient->select($query);

        $searchResultList->numFound = $result->getNumFound();

        /** @var SelectResultDocument $item */
        foreach ($result as $item) {
            $searchResultList->items[] = SolrPageDetailEntity::buildSearchResult($item);
        }

        return $searchResultList;
    }
}
