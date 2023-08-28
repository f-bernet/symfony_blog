<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'article_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();

        return $this->render('article/index.html.twig', ['articles' => $articles]);
    }

    #[Route('/article/{article}')]
    public function item(Article $article): Response
    {
        return $this->render('article/item.html.twig', ['article' => $article]);
    }

    #[Route('/article/create')]
    public function create(): Response
    {
        return $this->render('article/create.html.twig');
    }

    #[Route('/article/update')]
    public function update(): Response
    {
        return $this->render('article/update.html.twig');
    }

    #[Route('/article/delete')]
    public function delete(): Response
    {
        return $this->redirectToRoute('article_index');
    }
}