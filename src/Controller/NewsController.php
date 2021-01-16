<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(NewsRepository $newsRepository) : Response
    {
        $news = $newsRepository->findAll();
        return $this->render('news/index.html.twig', compact('news'));
    }

    /**
     * @Route("/news/{id<[0-9]+>}", name="app_news_show")
     */
    public function show(News $news) : Response {
        return $this->render('news/show.html.twig', compact('news'));
    }
}
