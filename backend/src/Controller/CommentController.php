<?php

namespace App\Controller;

use App\Entity\Comment;
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

    public function add(Request $request, ValidatorInterface $validator): Response
    {
        $data = json_decode($request->getContent(), true);
        $comment = (new Comment())
            ->setModifiedAt(new DateTime('now'))
            ->setTitle($data['title'])
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

        if ($comment->getId()) {
            return $this->json(['success' => true, 'comment' => $data], 201);
        }
    }
}
