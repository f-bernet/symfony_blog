<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleComment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article/{article}/comment')]

class ArticleCommentController extends AbstractController
{
    #[Route('/create', name: 'article_comment_create')]
    public function create(Request $request, EntityManagerInterface $entityManager, Article $article): Response
    {
        $comment = new ArticleComment();

        $data = $request->request->all();
        $key = array_key_first($data);
        $text = $data[array_key_first($data)];

        if(empty($text)){
            $this->addFlash('error', 'Empty comment');

            return $this->redirectToRoute('article_item', [
                'article' => $article
            ]);
        }

        $comment->setText($text);
        $comment->setArticle($article);

        if(str_contains($key, 'comment-response-')){
            $mainComment = $entityManager->getRepository(ArticleComment::class)->find(str_replace('comment-response-', '', $key));
            $comment->setCommentResponse($mainComment);
        }

        /** @var User $user */
        $user = $this->getUser();
        $comment->setUser($user);

        $entityManager->persist($comment);
        $entityManager->flush();

        $this->addFlash('success', 'Comment created');

        return $this->redirectToRoute('article_item', [
            'article' => $article->getId()
        ]);
    }

    #[Route('/{comment}/update', name: 'article_comment_update')]
    public function update(Request $request, EntityManagerInterface $entityManager, Article $article, ArticleComment $comment): Response
    {
        $text = $request->request->get('commentText');

        if(empty($text)){
            $this->addFlash('error', 'Empty comment');

            return $this->render('article/comment/update', [
                'comment' => $comment
            ]);
        }

        $comment->setText($text);
        $entityManager->persist($comment);
        $entityManager->flush();

        $this->addFlash('success', 'Comment created');

        return $this->redirectToRoute('article_item', [
            'article' => $article
        ]);
    }

    #[Route('/{comment}/delete', name: 'article_comment_delete')]
    public function delete(EntityManagerInterface $entityManager, ArticleComment $comment): Response
    {
        $comment->setText('[deleted]');
        $entityManager->persist($comment);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Comment deleted'
        );

        return $this->redirectToRoute('article_index');
    }
}