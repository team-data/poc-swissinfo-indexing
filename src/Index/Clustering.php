<?php

namespace App\Index;

use App\Model\Cluster;
use App\Model\HierarchyCluster;
use Pnz\SolariumClustering\QueryType\Result\Result as ClusterResult;
use Pnz\SolariumClustering\QueryType\Query as ClusterQuery;
use Solarium\Client;


class Clustering
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
     * @return Cluster[]
     */
    public function getClusters(string $engine, bool $excludeOthers = false, int $numResults = 200): array
    {
        $query = new ClusterQuery();

        $query->setRows($numResults);
        $query->setClusteringEngine($engine);
        $query->setFields(SolrPageDetailEntity::getSearchResultFields());

        /** @var ClusterResult $res */
        $res = $this->solrClient->execute($query);

        $return = [];
        foreach ($res->getClusters() as $cluster) {
            if ($excludeOthers && $cluster->isOtherTopics()) {
                continue;
            }

            $return[] = new Cluster(
                implode(', ', $cluster->getLabels()),
                $cluster->getScore(),
                $cluster->getIds(),
                $cluster->isOtherTopics()
            );
        }

        return $return;
    }

    public function getHierarchyClusters(string $rootName, string $engine, bool $exclude, int $numResults): HierarchyCluster
    {
        $data = $this->getClusters($engine, $exclude, $numResults);
        $root = new HierarchyCluster($rootName);
        foreach ($data as $cluster) {
            $node = new HierarchyCluster($cluster->label);
            foreach ($cluster->ids as $id) {
                $child = new HierarchyCluster($id);
                $child->size = 1;
                $node->children[] = $child;
            }
            $root->children[] = $node;
        }

        return $root;
    }

}