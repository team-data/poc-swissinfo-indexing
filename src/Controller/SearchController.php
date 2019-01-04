<?php

declare(strict_types=1);

namespace App\Controller;

use App\Index\Searcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @var Searcher
     */
    private $searcher;

    public function __construct(Searcher $searcher)
    {
        $this->searcher = $searcher;
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->query->get('q', '');

        $result = $this->searcher->search($query);

        return new JsonResponse($result);
    }
}
