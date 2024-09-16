<?php

namespace App\Entity\Creator;

use App\Entity\articleslanguages;
use App\Entity\articlestypes;
use App\Repository\Creator\articlesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: articlesRepository::class)]
class articles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $visible = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\Column(length: 255)]
    private ?string $thumbnail = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $pitch = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?articlestypes $type = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?articleslanguages $language = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): static
    {
        $this->visible = $visible;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getPitch(): ?string
    {
        return $this->pitch;
    }

    public function setPitch(string $pitch): static
    {
        $this->pitch = $pitch;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getType(): ?articlestypes
    {
        return $this->type;
    }

    public function setType(?articlestypes $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getLanguage(): ?articleslanguages
    {
        return $this->language;
    }

    public function setLanguage(?articleslanguages $language): static
    {
        $this->language = $language;

        return $this;
    }
}
