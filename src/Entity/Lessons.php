<?php

namespace App\Entity;

use App\Repository\LessonsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LessonsRepository::class)]
class Lessons
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lessons')]
    private ?Courses $course = null;

    #[ORM\ManyToOne(inversedBy: 'lessons')]
    private ?LessonsTypes $type = null;

    #[ORM\Column]
    private ?bool $is_premium = null;

    #[ORM\Column]
    private ?bool $is_visible = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(nullable: true)]
    private ?int $position = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $time = null;

    /**
     * @var Collection<int, CommentsLessons>
     */
    #[ORM\OneToMany(targetEntity: CommentsLessons::class, mappedBy: 'lesson', cascade: ['remove'])]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Collection $commentsLessons;

    /**
     * @var Collection<int, UserLesson>
     */
    #[ORM\OneToMany(targetEntity: UserLesson::class, mappedBy: 'lesson')]
    private Collection $userLessons;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $youtubeId = null;

    /**
     * @var Collection<int, PurchaseRegister>
     */
    #[ORM\OneToMany(targetEntity: PurchaseRegister::class, mappedBy: 'lesson')]
    private Collection $purchaseRegisters;

  

    public function __construct()
    {
        $this->commentsLessons = new ArrayCollection();
        $this->userLessons = new ArrayCollection();
        $this->purchaseRegisters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourse(): ?Courses
    {
        return $this->course;
    }

    public function setCourse(?Courses $course): static
    {
        $this->course = $course;

        return $this;
    }

    public function getType(): ?LessonsTypes
    {
        return $this->type;
    }

    public function setType(?LessonsTypes $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getIsPremium(): ?bool
    {
        return $this->is_premium;
    }

    public function setIsPremium(bool $is_premium): static
    {
        $this->is_premium = $is_premium;

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->is_visible;
    }

    public function setIsVisible(bool $is_visible): static
    {
        $this->is_visible = $is_visible;

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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getTime(): ?\DateTimeImmutable
    {
        return $this->time;
    }

    public function setTime(?\DateTimeImmutable $time): static
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return Collection<int, CommentsLessons>
     */
    public function getCommentsLessons(): Collection
    {
        return $this->commentsLessons;
    }

    public function addCommentsLesson(CommentsLessons $commentsLesson): static
    {
        if (!$this->commentsLessons->contains($commentsLesson)) {
            $this->commentsLessons->add($commentsLesson);
            $commentsLesson->setLesson($this);
        }

        return $this;
    }

    public function removeCommentsLesson(CommentsLessons $commentsLesson): static
    {
        if ($this->commentsLessons->removeElement($commentsLesson)) {
            // set the owning side to null (unless already changed)
            if ($commentsLesson->getLesson() === $this) {
                $commentsLesson->setLesson(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserLesson>
     */
    public function getUserLessons(): Collection
    {
        return $this->userLessons;
    }

    public function addUserLesson(UserLesson $userLesson): static
    {
        if (!$this->userLessons->contains($userLesson)) {
            $this->userLessons->add($userLesson);
            $userLesson->setLesson($this);
        }

        return $this;
    }

    public function removeUserLesson(UserLesson $userLesson): static
    {
        if ($this->userLessons->removeElement($userLesson)) {
            // set the owning side to null (unless already changed)
            if ($userLesson->getLesson() === $this) {
                $userLesson->setLesson(null);
            }
        }

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getYoutubeId(): ?string
    {
        return $this->youtubeId;
    }

    public function setYoutubeId(?string $youtubeId): static
    {
        $this->youtubeId = $youtubeId;

        return $this;
    }

    /**
     * @return Collection<int, PurchaseRegister>
     */
    public function getPurchaseRegisters(): Collection
    {
        return $this->purchaseRegisters;
    }

    public function addPurchaseRegister(PurchaseRegister $purchaseRegister): static
    {
        if (!$this->purchaseRegisters->contains($purchaseRegister)) {
            $this->purchaseRegisters->add($purchaseRegister);
            $purchaseRegister->setLesson($this);
        }

        return $this;
    }

    public function removePurchaseRegister(PurchaseRegister $purchaseRegister): static
    {
        if ($this->purchaseRegisters->removeElement($purchaseRegister)) {
            // set the owning side to null (unless already changed)
            if ($purchaseRegister->getLesson() === $this) {
                $purchaseRegister->setLesson(null);
            }
        }

        return $this;
    }

 

}
