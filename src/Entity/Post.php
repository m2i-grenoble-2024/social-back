<?php

namespace App\Entity;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints\NotBlank;

class Post {
    private ?int $id = null;
    #[NotBlank]
    private ?string $content = null;
    private ?DateTimeImmutable $postedAt = null;
    private ?User $author = null;
    private ?Post $respondTo = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPostedAt(): ?DateTimeImmutable
    {
        return $this->postedAt;
    }

    public function setPostedAt(?DateTimeImmutable $postedAt): self
    {
        $this->postedAt = $postedAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getRespondTo(): ?Post
    {
        return $this->respondTo;
    }

    public function setRespondTo(?Post $respondTo): self
    {
        $this->respondTo = $respondTo;

        return $this;
    }
}