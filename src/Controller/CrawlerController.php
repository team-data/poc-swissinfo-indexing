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
     * @Route("/crawler/start")
     */
    public function start(Request $request, Crawler $crawler): Response
    {

        $pageId = trim($request->request->get('page-id'));
        $recursive = $request->request->getBoolean('recursive');

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
