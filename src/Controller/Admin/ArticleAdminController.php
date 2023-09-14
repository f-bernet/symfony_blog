<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\Type\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin/article')]
class ArticleAdminController extends AbstractController
{
    #[Route('/create', name: 'article_create')]
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

        return $this->render('admin/article/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{article}/update', name: 'article_update')]
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
        return $this->render('admin/article/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{article}/delete', name: 'article_delete')]
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

