<?php

declare(strict_types=1);

namespace App\Index;

use App\Model\SearchResultList;
use Solarium\Client;
use Solarium\QueryType\MoreLikeThis\Query as MoreLikeThisQuery;
use Solarium\QueryType\MoreLikeThis\Result as MoreLikeThisResult;
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
        $query = $this->buildExistsQuery('id', $id);
        /** @var SelectResult $res */
        $res = $this->solrClient->execute($query);

        return 1 === $res->getNumFound();
    }

    public function search(string $queryString): SearchResultList
    {
        $query = new SelectQuery();
        $query->setQuery($queryString);
        $query->setFields(SolrPageDetailEntity::getSearchResultFields());

        if ('' === $queryString) {
            $query->addSort('date_date', 'DESC');
        }

        $dismax = $query->getEDisMax();
        $dismax->setQueryAlternative('*:*');
        $dismax->setQueryFields(implode(' ', [
            // 'contents_txt',
            'contents_en_txt',
            'title_txt^2',
        ]));

        // Computes: recip(x,m,a,b) = a/(m*x+b), where
        // x = ms(NOW,date_date)
        // a = b = 1
        // m = 3.16e-11
        $dismax->setBoostFunctions('recip(ms(NOW,date_date),3.16e-11,1,1)');

        /** @var SelectResult $result */
        $result = $this->solrClient->select($query);

        $searchResultList = new SearchResultList();
        $searchResultList->numFound = $result->getNumFound();

        /** @var SelectResultDocument $item */
        foreach ($result as $item) {
            $searchResultList->items[] = SolrPageDetailEntity::buildSearchResult($item);
        }

        return $searchResultList;
    }

    public function searchMoreLikeThis(string $url): SearchResultList
    {
        $query = new MoreLikeThisQuery();
        $query->setQuery(sprintf('canonical_s:"%s"', $query->getHelper()->escapeTerm($url)));
        $query->setFields(SolrPageDetailEntity::getSearchResultFields());
        $query->setMltFields(['contents_txt_en', 'title_txt']);
        $query->setMatchInclude(false);
        $query->setMinimumTermFrequency(3);
        $query->setMaximumQueryTerms(5);
        $query->setMinimumWordLength(3);
        $query->setMinimumDocumentFrequency(5);

        $query->setQueryFields(['contents_en_txt', 'title_txt^2']);
        // $query->setInterestingTerms('details');
        $query->setBoost(true);

        /** @var MoreLikeThisResult $result */
        $result = $this->solrClient->execute($query);

        $searchResultList = new SearchResultList();
        $searchResultList->numFound = $result->getNumFound();

        /** @var SelectResultDocument $item */
        foreach ($result as $item) {
            $searchResultList->items[] = SolrPageDetailEntity::buildSearchResult($item);
        }

        return $searchResultList;
    }

    public function existsPageDetailByUrl(string $url): bool
    {
        $query = $this->buildExistsQuery('canonical_s', $url);
        /** @var SelectResult $res */
        $res = $this->solrClient->execute($query);

        return 1 === $res->getNumFound();
    }

    private function buildExistsQuery(string $field, string $value): SelectQuery
    {
        $query = new SelectQuery();
        $query->setRows(0);
        $query->setFields(['id']);

        $filterQuery = new FilterQuery();
        $filterQuery
            ->setKey('exists-filter')
            ->setQuery(sprintf('%s:"%s"', $field, $query->getHelper()->escapeTerm($value)))
        ;

        return $query->addFilterQuery($filterQuery);
    }
}
