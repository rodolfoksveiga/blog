<?php

namespace App\Controller;

use App\Entity\Posts;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostsController extends AbstractController
{
    public function list(): Response
    {
        $posts = $this->getDoctrine()->getRepository(Posts::class)->findAll();
        
        if (!$posts) {
            return $this->json(['success' => false], 404);
        }

        return $this->json([
            'success' => true,
            'posts' => $posts,
        ]);
    }

    public function add(Request $request, ValidatorInterface $validator): Response
    {
        $post = (new Posts())
            ->setModifiedAt(new DateTime('now'))
            ->setTitle($request->request->get('title'))
            ->setBody($request->request->get('body'));
        $error = $validator->validate($post);

        if (count($error) > 0) {
            $errorMessages = [];

            /** @var Constraint $violantion */
            foreach($error as $violation) {
                $errorMessages[] = $violation->getPropertyPath() . ": " . $violation->getMessage();
            }
            return $this->json(['success' => false, 'error' => $errorMessages], 400);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        if ($post->getId()) {
            return $this->json(['success' => true, 'post' => $post], 201);
        }

    }
}
