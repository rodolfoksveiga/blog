<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Controller\PostController;
use App\Repository\PostRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentController extends AbstractController
{
    public function list(): Response
    {
        $comments = $this
            ->getDoctrine()
            ->getRepository(Comment::class)
            ->findAll();
        
        if (!$comments) {
            return $this->json([
                'success' => false,
                'error' => 'There is no comments in Comment table.'
            ], 404);
        }

        return $this->json(['success' => true, 'comments' => $comments], 201);
    }

    public function details(int $id): Response
    {
        $comment = $this
            ->getDoctrine()
            ->getRepository(Comment::class)
            ->find($id);
        
        if (!$comment) {
            return $this->json([
                'success' => false,
                'error' => 'No comment found for id ' . $id . '.'
            ], 404);
        }

        return $this->json([
            'success' => true,
            'comment' => $comment,
            'links' => '/comments/' . $id
        ], 201);
    }

    public function create(Request $request, ValidatorInterface $validator, PostRepository $postRepo): Response
    {
        $data = json_decode($request->getContent(), true);

        $post = $postRepo->find($data['postId']);

        if (!$post) {
            return $this->json([
                'success' => false,
                'error' => 'No post found for id ' . $data['postId'] . '.'
            ], 404);
        }

        $comment = (new Comment())
            ->setModifiedAt(new DateTime('now'))
            ->setPostId($post)
            ->setBody($data['body']);

        $error = $validator->validate($comment);
        if (count($error) > 0) {
            $errorMessages = [];

            /** @var Constraint $violantion */
            foreach($error as $violation) {
                $errorMessages[] = $violation->getPropertyPath() . ": " . $violation->getMessage();
            }
            return $this->json(['success' => false, 'error' => $errorMessages], 400);
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        $id = $comment->getId();
        if ($id) {
            return $this->json([
                'success' => true,
                'comment' => $comment,
                'links' => '/comments/' . $id
            ], 201);
        }
    }

    public function update(int $id, Request $request, ValidatorInterface $validator): Response
    {
        $comment = $this
            ->getDoctrine()
            ->getRepository(Comment::class)
            ->find($id);

        if (!$comment) {
            return $this->json([
                'success' => false,
                'error' => 'No comment found for id ' . $id . '.'
            ], 404);
        }
        
        $data = json_decode($request->getContent(), true);

        $comment = $comment
            ->setModifiedAt(new DateTime('now'))
            ->setBody($data['body']);

        $error = $validator->validate($comment);
        if (count($error) > 0) {
            $errorMessages = [];

            /** @var Constraint $violantion */
            foreach($error as $violation) {
                $errorMessages[] = $violation->getPropertyPath() . ": " . $violation->getMessage();
            }
            return $this->json(['success' => false, 'error' => $errorMessages], 400);
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        return $this->json([
            'success' => true,
            'comment' => $comment,
            'links' => '/comment/' . $id
        ]);
    }

    public function delete(int $id): Response
    {
        $comment = $this
            ->getDoctrine()
            ->getRepository(Comment::class)
            ->find($id);

        if (!$comment) {
            return $this->json([
                'success' => false,
                'error' => 'No comment found for id ' . $id . '.'
            ], 404);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        return $this->json([
            'success' => true
        ]);
    }
}
