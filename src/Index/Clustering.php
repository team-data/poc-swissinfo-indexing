<?php

namespace App\Index;


use App\Model\Cluster;
use App\Model\HierarchyCluster;
use Pnz\SolariumClustering\QueryType\Result\Result;
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
    public function getClusters(string $engine, bool $excludeOthers = false): array
    {
        $q = new ClusterQuery();

        $q->setRows(1000);
        $q->setClusteringEngine($engine);

        /** @var Result $res */
        $res = $this->solrClient->execute($q);

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

    public function getHierarchyClusters(string $rootName, string $engine, bool $exclude): HierarchyCluster
    {
        $data = $this->getClusters($engine, $exclude);
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