<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = ['ROLE_USER'];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $avatar = 'nobody.png';

    #[ORM\Column(length: 255, unique: true)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255)]
    private bool $donor = false;

    /**
     * @var Collection<int, Comments>
     */
    #[ORM\OneToMany(targetEntity: Comments::class, mappedBy: 'user')]
    private Collection $articles;

    /**
     * @var Collection<int, Comments>
     */
    #[ORM\OneToMany(targetEntity: Comments::class, mappedBy: 'user')]
    private Collection $comments;

    /**
     * @var Collection<int, Favorites>
     */
    #[ORM\OneToMany(targetEntity: Favorites::class, mappedBy: 'user')]
    private Collection $favorites;

    /**
     * @var Collection<int, CommentsLessons>
     */
    #[ORM\OneToMany(targetEntity: CommentsLessons::class, mappedBy: 'user')]
    private Collection $commentsLessons;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?int $credits = null;

    /**
     * @var Collection<int, UserLesson>
     */
    #[ORM\OneToMany(targetEntity: UserLesson::class, mappedBy: 'User')]
    private Collection $lesson;

    /**
     * @var Collection<int, UserLesson>
     */
    #[ORM\OneToMany(targetEntity: UserLesson::class, mappedBy: 'user')]
    private Collection $userLessons;

    /**
     * @var Collection<int, ConnectionRegister>
     */
    #[ORM\OneToMany(targetEntity: ConnectionRegister::class, mappedBy: 'user')]
    private Collection $connected_at;

    /**
     * @var Collection<int, ConnectionRegister>
     */
    #[ORM\OneToMany(targetEntity: ConnectionRegister::class, mappedBy: 'user')]
    private Collection $connectionRegisters;

    /**
     * @var Collection<int, PurchaseRegister>
     */
    #[ORM\OneToMany(targetEntity: PurchaseRegister::class, mappedBy: 'user')]
    private Collection $purchaseRegisters;

    #[ORM\Column(nullable: true)]
    private ?bool $isFirstPurchase = null;

    /**
     * @var Collection<int, CreditsPurchaseRegister>
     */
    #[ORM\OneToMany(targetEntity: CreditsPurchaseRegister::class, mappedBy: 'user')]
    private Collection $creditsPurchaseRegisters;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $StripeSessionId = null;

    /**
     * @var Collection<int, UserConnection>
     */
    #[ORM\OneToMany(targetEntity: UserConnection::class, mappedBy: 'user')]
    private Collection $ipAdress;

    /**
     * @var Collection<int, UserConnection>
     */
    #[ORM\OneToMany(targetEntity: UserConnection::class, mappedBy: 'user')]
    private Collection $userConnections;

    /**
     * @var Collection<int, PremiumLessonsAccess>
     */
    #[ORM\OneToMany(targetEntity: PremiumLessonsAccess::class, mappedBy: 'user')]
    private Collection $premiumLessonsAccesses;

    





    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->commentsLessons = new ArrayCollection();
        $this->lesson = new ArrayCollection();
        $this->userLessons = new ArrayCollection();
        $this->connected_at = new ArrayCollection();
        $this->connectionRegisters = new ArrayCollection();
        $this->purchaseRegisters = new ArrayCollection();
        $this->creditsPurchaseRegisters = new ArrayCollection();
        $this->ipAdress = new ArrayCollection();
        $this->userConnections = new ArrayCollection();
        $this->premiumLessonsAccesses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getDonor(): ?string
    {
        return $this->donor;
    }

    public function setDonor(bool  $donor): static
    {
        $this->donor = $donor;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Comments $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Comments $article): static
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favorites>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorites $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setUser($this);
        }

        return $this;
    }

    public function removeFavorite(Favorites $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getUser() === $this) {
                $favorite->setUser(null);
            }
        }

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
            $commentsLesson->setUser($this);
        }

        return $this;
    }

    public function removeCommentsLesson(CommentsLessons $commentsLesson): static
    {
        if ($this->commentsLessons->removeElement($commentsLesson)) {
            // set the owning side to null (unless already changed)
            if ($commentsLesson->getUser() === $this) {
                $commentsLesson->setUser(null);
            }
        }

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

    public function getCredits(): ?int
    {
        return $this->credits;
    }

    public function setCredits(int $credits): static
    {
        $this->credits = $credits;

        return $this;
    }

    /**
     * @return Collection<int, UserLesson>
     */
    public function getLesson(): Collection
    {
        return $this->lesson;
    }

    public function addLesson(UserLesson $lesson): static
    {
        if (!$this->lesson->contains($lesson)) {
            $this->lesson->add($lesson);
            $lesson->setUser($this);
        }

        return $this;
    }

    public function removeLesson(UserLesson $lesson): static
    {
        if ($this->lesson->removeElement($lesson)) {
            // set the owning side to null (unless already changed)
            if ($lesson->getUser() === $this) {
                $lesson->setUser(null);
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
            $userLesson->setUser($this);
        }

        return $this;
    }

    public function removeUserLesson(UserLesson $userLesson): static
    {
        if ($this->userLessons->removeElement($userLesson)) {
            // set the owning side to null (unless already changed)
            if ($userLesson->getUser() === $this) {
                $userLesson->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ConnectionRegister>
     */
    public function getConnectedAt(): Collection
    {
        return $this->connected_at;
    }

    public function addConnectedAt(ConnectionRegister $connectedAt): static
    {
        if (!$this->connected_at->contains($connectedAt)) {
            $this->connected_at->add($connectedAt);
            $connectedAt->setUser($this);
        }

        return $this;
    }

    public function removeConnectedAt(ConnectionRegister $connectedAt): static
    {
        if ($this->connected_at->removeElement($connectedAt)) {
            // set the owning side to null (unless already changed)
            if ($connectedAt->getUser() === $this) {
                $connectedAt->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ConnectionRegister>
     */
    public function getConnectionRegisters(): Collection
    {
        return $this->connectionRegisters;
    }

    public function addConnectionRegister(ConnectionRegister $connectionRegister): static
    {
        if (!$this->connectionRegisters->contains($connectionRegister)) {
            $this->connectionRegisters->add($connectionRegister);
            $connectionRegister->setUser($this);
        }

        return $this;
    }

    public function removeConnectionRegister(ConnectionRegister $connectionRegister): static
    {
        if ($this->connectionRegisters->removeElement($connectionRegister)) {
            // set the owning side to null (unless already changed)
            if ($connectionRegister->getUser() === $this) {
                $connectionRegister->setUser(null);
            }
        }

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
            $purchaseRegister->setUser($this);
        }

        return $this;
    }

    public function removePurchaseRegister(PurchaseRegister $purchaseRegister): static
    {
        if ($this->purchaseRegisters->removeElement($purchaseRegister)) {
            // set the owning side to null (unless already changed)
            if ($purchaseRegister->getUser() === $this) {
                $purchaseRegister->setUser(null);
            }
        }

        return $this;
    }

    public function isFirstPurchase(): ?bool
    {
        return $this->isFirstPurchase;
    }

    public function setFirstPurchase(?bool $isFirstPurchase): static
    {
        $this->isFirstPurchase = $isFirstPurchase;

        return $this;
    }

    /**
     * @return Collection<int, CreditsPurchaseRegister>
     */
    public function getCreditsPurchaseRegisters(): Collection
    {
        return $this->creditsPurchaseRegisters;
    }

    public function addCreditsPurchaseRegister(CreditsPurchaseRegister $creditsPurchaseRegister): static
    {
        if (!$this->creditsPurchaseRegisters->contains($creditsPurchaseRegister)) {
            $this->creditsPurchaseRegisters->add($creditsPurchaseRegister);
            $creditsPurchaseRegister->setUser($this);
        }

        return $this;
    }

    public function removeCreditsPurchaseRegister(CreditsPurchaseRegister $creditsPurchaseRegister): static
    {
        if ($this->creditsPurchaseRegisters->removeElement($creditsPurchaseRegister)) {
            // set the owning side to null (unless already changed)
            if ($creditsPurchaseRegister->getUser() === $this) {
                $creditsPurchaseRegister->setUser(null);
            }
        }

        return $this;
    }

    public function getStripeSessionId(): ?string
    {
        return $this->StripeSessionId;
    }

    public function setStripeSessionId(?string $StripeSessionId): static
    {
        $this->StripeSessionId = $StripeSessionId;

        return $this;
    }

    /**
     * @return Collection<int, UserConnection>
     */
    public function getIpAdress(): Collection
    {
        return $this->ipAdress;
    }

    public function addIpAdress(UserConnection $ipAdress): static
    {
        if (!$this->ipAdress->contains($ipAdress)) {
            $this->ipAdress->add($ipAdress);
            $ipAdress->setUser($this);
        }

        return $this;
    }

    public function removeIpAdress(UserConnection $ipAdress): static
    {
        if ($this->ipAdress->removeElement($ipAdress)) {
            // set the owning side to null (unless already changed)
            if ($ipAdress->getUser() === $this) {
                $ipAdress->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserConnection>
     */
    public function getUserConnections(): Collection
    {
        return $this->userConnections;
    }

    public function addUserConnection(UserConnection $userConnection): static
    {
        if (!$this->userConnections->contains($userConnection)) {
            $this->userConnections->add($userConnection);
            $userConnection->setUser($this);
        }

        return $this;
    }

    public function removeUserConnection(UserConnection $userConnection): static
    {
        if ($this->userConnections->removeElement($userConnection)) {
            // set the owning side to null (unless already changed)
            if ($userConnection->getUser() === $this) {
                $userConnection->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PremiumLessonsAccess>
     */
    public function getPremiumLessonsAccesses(): Collection
    {
        return $this->premiumLessonsAccesses;
    }

    public function addPremiumLessonsAccess(PremiumLessonsAccess $premiumLessonsAccess): static
    {
        if (!$this->premiumLessonsAccesses->contains($premiumLessonsAccess)) {
            $this->premiumLessonsAccesses->add($premiumLessonsAccess);
            $premiumLessonsAccess->setUser($this);
        }

        return $this;
    }

    public function removePremiumLessonsAccess(PremiumLessonsAccess $premiumLessonsAccess): static
    {
        if ($this->premiumLessonsAccesses->removeElement($premiumLessonsAccess)) {
            // set the owning side to null (unless already changed)
            if ($premiumLessonsAccess->getUser() === $this) {
                $premiumLessonsAccess->setUser(null);
            }
        }

        return $this;
    }

    
}
