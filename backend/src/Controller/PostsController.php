<?php

namespace App\Controller;

use App\Entity\Posts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    public function list(): Response
    {
        $posts = $this->getDoctrine()->getRepository(Posts::class)->findAll();
        
        return $this->json([
            'success' => true,
            'posts' => $posts,
        ]);
    }
}
