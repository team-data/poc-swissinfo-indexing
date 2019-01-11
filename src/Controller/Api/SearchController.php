<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Index\Searcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
    public function search(Request $request): JsonResponse
    {
        $query = $request->query->get('q', '');

        $result = $this->searcher->search($query);

        return new JsonResponse($result);
    }

    /**
     * @Route("/search-like")
     */
    public function searchLike(Request $request): JsonResponse
    {
        $url = trim($request->query->get('url'));
        if (!$url) {
            throw new BadRequestHttpException('Invalid URL provided');
        }

        if (!$this->searcher->existsPageDetailByUrl($url)) {
            throw new NotFoundHttpException(sprintf(
                'Page with url %s not found in the index',
                $url
            ));
        }

        $result = $this->searcher->searchMoreLikeThis($url);

        return new JsonResponse($result);
    }
}
