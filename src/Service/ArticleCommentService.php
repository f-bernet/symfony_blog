<?php

namespace App\Service;

use App\Entity\ArticleComment;
use Doctrine\Common\Collections\Collection;

class ArticleCommentService
{
    public function generateComments(array $comments): array
    {
        $resultComments = [
            'mainComment' => [],
            'responseComment' => []
        ];

        /** @var ArticleComment $comment */
        foreach ($comments as $comment) {
            if (empty($comment->getCommentResponse())) {
                $resultComments['mainComment'][] = $comment;
            } else {
                $resultComments['responseComment'][$comment->getCommentResponse()->getId()][] = $comment;
            }
        }

        return $resultComments;
    }
}