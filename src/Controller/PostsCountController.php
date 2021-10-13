<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostsCountController extends AbstractController

{
    public function __construct(private PostRepository $postRepository)
    {
    }

    public function __invoke(): int
    {
        return $this->postRepository->count([]);
    }
}
