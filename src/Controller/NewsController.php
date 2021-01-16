<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(NewsRepository $newsRepository)
    {
        $news = $newsRepository->findAll();
        return $this->render('news/index.html.twig', compact('news'));
    }
}
