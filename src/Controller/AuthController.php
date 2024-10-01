<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class AuthController  extends AbstractController {

    public function __construct(private UserRepository $repo) {}

    #[Route('/api/user', methods: 'POST')]
    public function register(
        #[MapRequestPayload] User $user, 
        UserPasswordHasherInterface $hasher):JsonResponse {
            if($this->repo->findByIdentifier($user->getEmail())) {
                return $this->json('Email already in used', 400);
            }
            if($this->repo->findByIdentifier($user->getUsername())) {
                return $this->json('Username already in used', 400);
            }
            $user->setCreatedAt(new \DateTimeImmutable());
            $hashedPassword = $hasher->hashPassword($user,$user->getPassword());
            $user->setPassword($hashedPassword);
            $user->setRole('ROLE_USER');
            $this->repo->persist($user);
            return $this->json($user, 201);
    }
    #[Route('/api/user', methods:'GET')]
    public function getConnectedUser() {
        return $this->json($this->getUser());
    }

    #[Route('/api/user/{username}', methods:'PATCH')]
    public function promoteUser(string $username) {
        $userToPromote = $this->repo->findByIdentifier($username);
        if(!$userToPromote)  {
            throw new NotFoundHttpException('User does not exists');
        }
        $userToPromote->setRole('ROLE_MODO');
        return $this->json($userToPromote);
    }
}