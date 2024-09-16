<?php

namespace App\Entity;

use App\Entity\Creator\articles;
use App\Repository\ArticlesLanguagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlesLanguagesRepository::class)]
class ArticlesLanguages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $language = null;

    /**
     * @var Collection<int, articles>
     */
    #[ORM\OneToMany(targetEntity: articles::class, mappedBy: 'language')]
    private Collection $dsfcv;

    /**
     * @var Collection<int, articles>
     */
    #[ORM\OneToMany(targetEntity: articles::class, mappedBy: 'language')]
    private Collection $articles;

    public function __construct()
    {
        $this->dsfcv = new ArrayCollection();
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
     * @return Collection<int, articles>
     */
    public function getDsfcv(): Collection
    {
        return $this->dsfcv;
    }

    public function addDsfcv(articles $dsfcv): static
    {
        if (!$this->dsfcv->contains($dsfcv)) {
            $this->dsfcv->add($dsfcv);
            $dsfcv->setLanguage($this);
        }

        return $this;
    }

    public function removeDsfcv(articles $dsfcv): static
    {
        if ($this->dsfcv->removeElement($dsfcv)) {
            // set the owning side to null (unless already changed)
            if ($dsfcv->getLanguage() === $this) {
                $dsfcv->setLanguage(null);
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
            $article->setLanguage($this);
        }

        return $this;
    }

    public function removeArticle(articles $article): static
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getLanguage() === $this) {
                $article->setLanguage(null);
            }
        }

        return $this;
    }
}
