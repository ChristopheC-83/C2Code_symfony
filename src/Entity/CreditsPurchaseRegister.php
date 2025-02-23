<?php

namespace App\Entity;

use App\Repository\CreditsPurchaseRegisterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CreditsPurchaseRegisterRepository::class)]
class CreditsPurchaseRegister
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'creditsPurchaseRegisters')]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $purchased_at = null;

    #[ORM\Column]
    private ?int $creditsQuantity = null;

    #[ORM\Column]
    private ?int $purchaseAmount = null;

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

    public function getPurchasedAt(): ?\DateTimeImmutable
    {
        return $this->purchased_at;
    }

    public function setPurchasedAt(\DateTimeImmutable $purchased_at): static
    {
        $this->purchased_at = $purchased_at;

        return $this;
    }

    public function getCreditsQuantity(): ?int
    {
        return $this->creditsQuantity;
    }

    public function setCreditsQuantity(int $creditsQuantity): static
    {
        $this->creditsQuantity = $creditsQuantity;

        return $this;
    }

    public function getPurchaseAmount(): ?int
    {
        return $this->purchaseAmount;
    }

    public function setPurchaseAmount(int $purchaseAmount): static
    {
        $this->purchaseAmount = $purchaseAmount;

        return $this;
    }
}
