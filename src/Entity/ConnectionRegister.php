<?php

namespace App\Entity;

use App\Repository\ConnectionRegisterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConnectionRegisterRepository::class)]
class ConnectionRegister
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'connectionRegisters')]
    private ?user $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $connected_at = null;

    #[ORM\Column(length: 255)]
    private ?string $ip_address = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getConnectedAt(): ?\DateTimeImmutable
    {
        return $this->connected_at;
    }

    public function setConnectedAt(\DateTimeImmutable $connected_at): static
    {
        $this->connected_at = $connected_at;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ip_address;
    }

    public function setIpAddress(string $ip_address): static
    {
        $this->ip_address = $ip_address;

        return $this;
    }
}
