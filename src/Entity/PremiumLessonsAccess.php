<?php

namespace App\Entity;

use App\Repository\PremiumLessonsAccessRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PremiumLessonsAccessRepository::class)]
class PremiumLessonsAccess
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'premiumLessonsAccesses')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'premiumLessonsAccesses')]
    private ?Lessons $lesson = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $viewedAt = null;

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

    public function getViewedAt(): ?\DateTimeImmutable
    {
        return $this->viewedAt;
    }

    public function setViewedAt(\DateTimeImmutable $viewedAt): static
    {
        $this->viewedAt = $viewedAt;

        return $this;
    }
}
