<?php

namespace App\Entity;

use App\Entity\Creator\articles;
use App\Repository\ArticlesTypesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlesTypesRepository::class)]
class ArticlesTypes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * @var Collection<int, articles>
     */
    #[ORM\OneToMany(targetEntity: articles::class, mappedBy: 'type')]
    private Collection $language;

    /**
     * @var Collection<int, articles>
     */
    #[ORM\OneToMany(targetEntity: articles::class, mappedBy: 'type')]
    private Collection $articles;

    public function __construct()
    {
        $this->language = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, articles>
     */
    public function getLanguage(): Collection
    {
        return $this->language;
    }

    public function addLanguage(articles $language): static
    {
        if (!$this->language->contains($language)) {
            $this->language->add($language);
            $language->setType($this);
        }

        return $this;
    }

    public function removeLanguage(articles $language): static
    {
        if ($this->language->removeElement($language)) {
            // set the owning side to null (unless already changed)
            if ($language->getType() === $this) {
                $language->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, articles>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(articles $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setType($this);
        }

        return $this;
    }

    public function removeArticle(articles $article): static
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getType() === $this) {
                $article->setType(null);
            }
        }

        return $this;
    }
}
