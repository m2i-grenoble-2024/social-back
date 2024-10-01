<?php

namespace App\Entity;

use DateTimeImmutable;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class User implements UserInterface, PasswordAuthenticatedUserInterface {
    private ?int $id = null;
    #[Email]
    #[NotBlank]
    private ?string $email = null;
    #[NotBlank]
    private ?string $username = null;
    private ?DateTimeImmutable $createdAt = null;
    private ?DateTimeImmutable $banDate = null;
    #[NotBlank]
    // #[PasswordStrength] //annotation qui vérifie que le mot de passe est pas pourri, par défaut c'est sur une strength medium
    private ?string $password = null;
    private ?string $role = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles(): array {
        return [$this->role];
    }
    public function eraseCredentials(): void{}
    public function getUserIdentifier(): string {
        return $this->username; //Pour indiquer qu'on se loggera avec le username plutôt que l'email
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getBanDate(): ?DateTimeImmutable
    {
        return $this->banDate;
    }

    public function setBanDate(?DateTimeImmutable $banDate): self
    {
        $this->banDate = $banDate;

        return $this;
    }
}