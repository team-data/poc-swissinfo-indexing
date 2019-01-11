<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Index\Clustering;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClusteringController extends AbstractController
{
    /**
     * @var Clustering
     */
    private $clustering;

    public function __construct(Clustering $clustering)
    {
        $this->clustering = $clustering;
    }

    /**
     * @Route("/clustering/data")
     */
    public function data(Request $request): Response
    {
        $engine = $request->query->getAlpha('engine', 'lingo');
        $exclude = $request->query->getBoolean('exclude');
        $numResults = $request->query->getInt('results', 100);

        $data = $this->clustering->getClusters($engine, $exclude, $numResults);

        return $this->json($data);
    }

    /**
     * @Route("/clustering/data-hierarchy")
     */
    public function dataHierarchy(Request $request): Response
    {
        $engine = $request->query->getAlpha('engine', 'lingo');
        $exclude = $request->query->getBoolean('exclude');
        $numResults = $request->query->getInt('results', 100);

        $data = $this->clustering->getHierarchyClusters('Swissinfo.ch', $engine, $exclude, $numResults);

        return $this->json($data);
    }
}
