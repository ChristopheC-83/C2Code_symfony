<?php

namespace App\Entity;

use App\Repository\PurchaseRegisterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseRegisterRepository::class)]
class PurchaseRegister
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'purchaseRegisters')]
    private ?user $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $purchased_at = null;

    #[ORM\Column]
    private ?int $price_purchased = null;

    #[ORM\ManyToOne(inversedBy: 'purchaseRegisters')]
    private ?lessons $lesson = null;

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

    public function getPurchasedAt(): ?\DateTimeImmutable
    {
        return $this->purchased_at;
    }

    public function setPurchasedAt(\DateTimeImmutable $purchased_at): static
    {
        $this->purchased_at = $purchased_at;

        return $this;
    }

    public function getPricePurchased(): ?int
    {
        return $this->price_purchased;
    }

    public function setPricePurchased(int $price_purchased): static
    {
        $this->price_purchased = $price_purchased;

        return $this;
    }

    public function getLesson(): ?lessons
    {
        return $this->lesson;
    }

    public function setLesson(?lessons $lesson): static
    {
        $this->lesson = $lesson;

        return $this;
    }
}
