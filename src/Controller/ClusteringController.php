<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClusteringController extends AbstractController
{
    /**
     * @Route("/clustering")
     */
    public function index()
    {
        return $this->render('clustering.html.twig', [
            'title' => 'Clustering',
            'results' => 100,
        ]);
    }
}
