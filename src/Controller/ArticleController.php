<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\Type\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function item(Article $article): Response
    {
        return $this->render('article/item.html.twig', ['article' => $article]);
    }

    #[Route('/article/create', name: 'article_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Article created'
            );

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/article/{article}/update', name: 'article_update')]
    public function update(Request $request, EntityManagerInterface $entityManager, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Article updated'
            );

            return $this->redirectToRoute('article_index');
        }
        return $this->render('article/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/article/{article}/delete', name: 'article_delete')]
    public function delete(EntityManagerInterface $entityManager, Article $article): Response
    {
        $entityManager->remove($article);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Article deleted'
        );

        return $this->redirectToRoute('article_index');
    }
}