<?php

namespace App\Entity;

use App\Entity\Articles;
use App\Repository\LanguagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LanguagesRepository::class)]
class Languages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $language = null;

    /**
     * @var Collection<int, Articles>
     */
    #[ORM\OneToMany(targetEntity: Articles::class, mappedBy: 'languages')]
    private Collection $articles;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $iconLanguage = null;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return Collection<int, Articles>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setLanguages($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): static
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getLanguages() === $this) {
                $article->setLanguages(null);
            }
        }

        return $this;
    }

    public function getIconLanguage(): ?string
    {
        return $this->iconLanguage;
    }

    public function setIconLanguage(?string $iconLanguage): static
    {
        $this->iconLanguage = $iconLanguage;

        return $this;
    }
}
