<?php

namespace App\Controller;

use App\Index\Searcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/search")
     */
    public function index(Request $request)
    {
        $queryString = $request->get('keywords', '');
        $results = $this->searcher->search($queryString);

        return $this->render('search/index.html.twig', [
            'keywords' => $queryString,
            'results' => $results,
        ]);
    }
    /**
     * @Route("/search-like")
     */
    public function like(Request $request)
    {
        $url = $request->get('url', '');
        $results = $this->searcher->searchMoreLikeThis($url);
        $debug = $request->query->getBoolean('debug');

        return $this->render('search/like.html.twig', [
            'url' => $url,
            'debug' => $debug,
            'results' => $results,
        ]);
    }
}
