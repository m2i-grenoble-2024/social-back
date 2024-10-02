<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class AuthController  extends AbstractController {

    public function __construct(
        private UserRepository $repo, 
        private MailerInterface $mailer,
        private JWTTokenManagerInterface $tokenManager) {}

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
            $user->setRole('ROLE_INACTIVE');
            $this->repo->persist($user);

            $this->sendValidationMail($user);

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
        $this->repo->update($userToPromote);
        return $this->json($userToPromote);
    }

    #[Route('/api/user/validate', methods: 'POST')]
    public function validateEmail() {
        $user = $this->getUser();
        $user->setRole('ROLE_USER');
        $this->repo->update($user);

        return $this->json(null, 204);
    }

    #[Route('/api/user/validate', methods: 'GET')]
    public function resendValidationMail(#[MapQueryParameter] string $email) {
        $user = $this->repo->findByIdentifier($email);
        if(!$user) {
            throw new NotFoundHttpException('User does not exists');
        }
        if($user->getRole() != 'ROLE_INACTIVE') {
            throw new BadRequestHttpException('User already valid');
        }
        $this->sendValidationMail($user);
        return $this->json(null, 204);
    }
    
    public function sendValidationMail(User $user) {
        
        $jwt = $this->tokenManager->create($user);
        $email = new Email();
        $email->subject('Social Symfony - Validate your email')
        ->from('m2i-social@bloup.com')
        ->to($user->getEmail())
        ->html('<p>Salut tu es inscrit bravo, super. <a href="http://localhost:4200/validate-email/'.$jwt.'">Cliquez sur ce lien pour valider votre compte</a></p>');
        
        $this->mailer->send($email);
    }
}