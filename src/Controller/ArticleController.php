<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleComment;
use App\Service\ArticleCommentService;
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

    #[Route('/article/{article}/index', name: 'article_item')]
    public function item(EntityManagerInterface $entityManager, ArticleCommentService $articleCommentService, Article $article): Response
    {
        $comments = $entityManager->getRepository(ArticleComment::class)->findBy(['article' => $article->getId()], ['createdAt' => 'ASC']);
        $comments = $articleCommentService->generateComments($comments);

        return $this->render('article/item.html.twig', [
            'article' => $article,
            'comments' => $comments
        ]);
    }
}