<?php

namespace App\Entity;

use App\Repository\UserLessonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserLessonRepository::class)]
class UserLesson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userLessons')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userLessons')]
    private ?Lessons $lesson = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $purchasedAt = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $youtubeId = null;

    #[ORM\Column(type: Types::TEXT, nullable:true)]
    private ?string $secureToken = null;

    #[ORM\Column]
    private ?int $purchasedPrice = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getLesson(): ?Lessons
    {
        return $this->lesson;
    }

    public function setLesson(?Lessons $lesson): static
    {
        $this->lesson = $lesson;

        return $this;
    }

    public function getPurchasedAt(): ?\DateTimeImmutable
    {
        return $this->purchasedAt;
    }

    public function setPurchasedAt(\DateTimeImmutable $purchasedAt): static
    {
        $this->purchasedAt = $purchasedAt;

        return $this;
    }

    public function getYoutubeId(): ?string
    {
        return $this->youtubeId;
    }

    public function setYoutubeId(string $youtubeId): static
    {
        $this->youtubeId = $youtubeId;

        return $this;
    }

    public function getSecureToken(): ?string
    {
        return $this->secureToken;
    }

    public function setSecureToken(string $secureToken): static
    {
        $this->secureToken = $secureToken;

        return $this;
    }

    public function getPurchasedPrice(): ?int
    {
        return $this->purchasedPrice;
    }

    public function setPurchasedPrice(int $purchasedPrice): static
    {
        $this->purchasedPrice = $purchasedPrice;

        return $this;
    }
}
