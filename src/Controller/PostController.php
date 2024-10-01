<?php

namespace App\Controller;
use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/post')]
class PostController extends AbstractController {
    public function __construct(private PostRepository $repo) {}
    
    #[Route(methods:'POST')]
    public function add(#[MapRequestPayload] Post $post) {
        $post->setPostedAt(new \DateTimeImmutable());
        $post->setAuthor($this->getUser());
        $this->repo->persist($post);
        return $this->json($post, 201);
    }

    #[Route(methods:'GET')]
    public function all(#[MapQueryParameter] int $page = 1) {
        

        return $this->json(
            $this->repo->findAll(($page-1)*15, 15)
        );
    }
}