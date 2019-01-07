<?php

declare(strict_types=1);

namespace App\Controller;

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
     * @Route("/clustering", name="clustering")
     */
    public function index()
    {
        return $this->render('clustering.html.twig', [
            'title' => 'Clustering',
        ]);
    }

    /**
     * @Route(
     *     "/clustering-data",
     *     name="clustering_data",
     * )
     */
    public function data(Request $request): Response
    {
        $engine = $request->query->getAlpha('engine', 'lingo');
        $exclude = $request->query->getBoolean('exclude', false);

        $data = $this->clustering->getClusters($engine, $exclude);

        return $this->json($data);
    }

    /**
     * @Route(
     *     "/clustering-data-hierarchy",
     *     name="clustering_data_hierarchy",
     * )
     */
    public function dataHierarchy(Request $request): Response
    {
        $engine = $request->query->getAlpha('engine', 'lingo');
        $exclude = $request->query->getBoolean('exclude');
        $data = $this->clustering->getHierarchyClusters('Swissinfo.ch', $engine, $exclude);

        return $this->json($data);
    }
}
