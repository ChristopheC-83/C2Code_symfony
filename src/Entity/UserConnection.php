<?php

namespace App\Entity;

use App\Repository\UserConnectionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserConnectionRepository::class)]
class UserConnection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userConnections')]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $ipAddress = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $connectionAt = null;

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

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): static
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getConnectionAt(): ?\DateTimeImmutable
    {
        return $this->connectionAt;
    }

    public function setConnectionAt(\DateTimeImmutable $connectionAt): static
    {
        $this->connectionAt = $connectionAt;

        return $this;
    }
}
