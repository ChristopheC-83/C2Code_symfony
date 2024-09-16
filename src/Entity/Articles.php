<?php

namespace App\Entity;

use App\Entity\Languages;
use App\Entity\Types as ArticleTypes;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types as DoctrineTypes; // Alias ajouté

#[ORM\Entity(repositoryClass: ArticlesRepository::class)]
class Articles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?Types $types = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?Languages $languages = null;

    #[ORM\Column]
    private ?bool $visible = null;

    #[ORM\Column(length: 255)]
    private ?string $thumbnail = null;

    #[ORM\Column(type: DoctrineTypes::TEXT)] // Utilisation de l'alias pour TEXT
    private ?string $pitch = null;

    #[ORM\Column(type: DoctrineTypes::TEXT, nullable: true)] // Utilisation de l'alias pour TEXT
    private ?string $text = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTypes(): ?Types
    {
        return $this->types;
    }

    public function setTypes(?Types $types): static
    {
        $this->types = $types;

        return $this;
    }

    public function getLanguages(): ?Languages
    {
        return $this->languages;
    }

    public function setLanguages(?Languages $languages): static
    {
        $this->languages = $languages;

        return $this;
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }
}
