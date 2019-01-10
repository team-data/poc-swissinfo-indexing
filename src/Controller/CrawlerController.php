<?php

namespace App\Controller;

use App\Crawler\Crawler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CrawlerController extends AbstractController
{
    /**
     * @Route("/crawler-start", name="crawler-start")
     */
    public function index(Request $request, Crawler $crawler): Response
    {

        $pageId = $request->query->get('page-id');
        $recursive = $request->query->getBoolean('recursive');

        $started = false;
        if ($pageId) {
            $crawler->startCrawlingFromPageDetail($pageId, $recursive);
            $started = true;
        }

        return $this->render('crawler/index.html.twig', [
            'page_id' => $pageId,
            'started' => $started,
        ]);
    }
}
