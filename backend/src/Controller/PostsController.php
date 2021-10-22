<?php

namespace App\Controller;

use App\Entity\Posts;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    public function add(Request $request): Response
    {
        $post = (new Posts())
            ->setModifiedAt(new DateTime('now'))
            ->setTitle($request->request->get('title'))
            ->setBody($request->request->get('body'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        if ($post->getId()) {
            return $this->json(['success' => true, 'post' => $post], 201);
        }

        return $this->json(['success' => false, 400]);
    }
}
