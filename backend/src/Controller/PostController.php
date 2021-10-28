<?php

namespace App\Controller;

use App\Entity\Post;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostController extends AbstractController
{
    public function list(): Response
    {
        $posts = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();
        
        if (!$posts) {
            return $this->json([
                'success' => false,
                'error' => 'There is no posts in Post table.'
            ], 404);
        }

        return $this->json([
            'success' => true,
            'posts' => $posts,
            'links' => '/posts'
        ], 201);
    }

    public function details(int $id): Response
    {
        $post = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);
        
        if (!$post) {
            return $this->json([
                'success' => false,
                'error' => 'No post found for id ' . $id
            ], 404);
        }

        return $this->json([
            'success' => true,
            'post' => $post,
            'links' => '/posts/' . $id
        ], 201);
    }

    public function create(Request $request, ValidatorInterface $validator): Response
    {
        $data = json_decode($request->getContent(), true);
        $post = (new Post())
            ->setModifiedAt(new DateTime('now'))
            ->setTitle($data['title'])
            ->setBody($data['body']);
        
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

        $id = $post->getId();
        if ($id) {
            return $this->json([
                'success' => true,
                'post' => $data,
                'links' => '/posts/' . $id
            ], 201);
        }
    }
    
    public function update(int $id, Request $request): Response
    {
        $post = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        if (!$post) {
            return $this->json([
                'success' => false,
                'error' => 'No post found for id ' . $id . '.'
            ], 404);
        }
        
        $data = json_decode($request->getContent(), true);

        $post = $post
            ->setModifiedAt(new DateTime('now'))
            ->setTitle($data['title'])
            ->setBody($data['body']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        return $this->json([
            'success' => true,
            'post' => $post,
            'links' => '/posts/' . $id
        ]);
    }

    public function delete(int $id): Response
    {
        $post = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        if (!$post) {
            return $this->json([
                'success' => false,
                'error' => 'No post found for id ' . $id
            ], 404);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return $this->json([
            'success' => true
        ]);
    }
}
