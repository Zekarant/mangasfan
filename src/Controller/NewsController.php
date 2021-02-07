<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods="GET")
     */
    public function index(NewsRepository $newsRepository) : Response
    {
        $news = $newsRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('news/index.html.twig', compact('news'));
    }

    /**
     * @Route("/news/{id<[0-9]+>}", name="app_news_show", methods="GET")
     */
    public function show(News $news) : Response {
        return $this->render('news/show.html.twig', compact('news'));
    }

    /**
     * @Route("/news/create", name="app_news_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em) : Response
    {
        $news = new News;
        $form = $this->createForm(NewsType::class, $news);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($news);
            $em->flush();

            $this->addFlash('success', 'La news a bien été créée !');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('news/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/news/{id<[0-9]+>}/edit", name="app_news_edit", methods={"GET", "PUT"})
     */
    public function edit(Request $request, EntityManagerInterface $em, News $news) : Response {

        $form = $this->createForm(NewsType::class, $news, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();

            $this->addFlash('success', 'La news a bien été éditée !');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('news/edit.html.twig', [
            'news' => $news,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/news/{id<[0-9]+>}/delete", name="app_news_delete", methods={"DELETE"})
     */
    public function delete(Request $request, News $news, EntityManagerInterface $em) : Response {
        if ($this->isCsrfTokenValid('news_delete_' . $news->getId(), $request->request->get('csrf_token'))){
            $em->remove($news);
            $em->flush();

            $this->addFlash('danger', 'La news a bien été supprimée !');
        }

        return $this->redirectToRoute('app_home');

    }
}
