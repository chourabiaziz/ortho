<?php
namespace App\Entity;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

 use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: "L'adresse e-mail ne peut pas être vide.")]
 
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: "champ obligatoir.")]
    #[Assert\Email(message: "L'adresse e-mail n'est pas valide.", )]
     private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;





    
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "champ obligatoir.")]
     #[Assert\Regex(pattern:"/^[a-zA-Z\s]{2,}$/",  message:"nom doit avoir plus de 2 caractères")]
    #[Assert\Regex(pattern:"/^[\p{L}\s]*$/u",  message:"Le nom ne doit contenir que des lettres et des espaces")]
      private ?string $nom = null;

    
    #[ORM\Column(length: 255)]
    private ?string $image = null;
 
    public function getImage(): ?string
    {
        return $this->image;
    }
    public function setImage(string $image): self
    {
        $this->image = $image;
    
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Facture::class, mappedBy: 'createdby')]
    private Collection $factures;

    #[ORM\OneToMany(targetEntity: Facture::class, mappedBy: 'reciever')]
    private Collection $facturesrecieved;

    #[ORM\OneToMany(targetEntity: Achat::class, mappedBy: 'personne')]
    private Collection $achats;

    #[ORM\Column(length: 255, nullable: true)]
     #[Assert\Regex(
        pattern: '/^\d{8}$/',
        message: " matricule doit etre exactement 8 caractéres ."
    )]
      private ?string $matricule = null;

    #[ORM\Column(nullable: true)]
    private ?bool $accepted = null;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'sender')]
    private Collection $comments;

    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'reciever')]
    private Collection $notifications;

    #[ORM\OneToOne(mappedBy: 'createdby', cascade: ['persist', 'remove'])]
    private ?Profil $profil = null;

    #[ORM\OneToMany(targetEntity: FichePatient::class, mappedBy: 'createdby')]
    private Collection $fichePatients;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $test = null;

    public function __construct()
    {
        
        $this->factures = new ArrayCollection();
        $this->facturesrecieved = new ArrayCollection();
        $this->achats = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->fichePatients = new ArrayCollection();
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
    public function getPassword(): string
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }
 
 
    


    // Implement custom unserialization logic

        /**
         * @return Collection<int, Facture>
         */
        public function getFactures(): Collection
        {
            return $this->factures;
        }

        

 
        /**
         * @return Collection<int, Facture>
         */
        public function getFacturesrecieved(): Collection
        {
            return $this->facturesrecieved;
        }

        public function addFacturesrecieved(Facture $facturesrecieved): static
        {
            if (!$this->facturesrecieved->contains($facturesrecieved)) {
                $this->facturesrecieved->add($facturesrecieved);
                $facturesrecieved->setReciever($this);
            }

            return $this;
        }

        public function removeFacturesrecieved(Facture $facturesrecieved): static
        {
            if ($this->facturesrecieved->removeElement($facturesrecieved)) {
                // set the owning side to null (unless already changed)
                if ($facturesrecieved->getReciever() === $this) {
                    $facturesrecieved->setReciever(null);
                }
            }

            return $this;
        }

        /**
         * @return Collection<int, Achat>
         */
        public function getAchats(): Collection
        {
            return $this->achats;
        }

        public function addAchat(Achat $achat): static
        {
            if (!$this->achats->contains($achat)) {
                $this->achats->add($achat);
                $achat->setPersonne($this);
            }

            return $this;
        }

        public function removeAchat(Achat $achat): static
        {
            if ($this->achats->removeElement($achat)) {
                // set the owning side to null (unless already changed)
                if ($achat->getPersonne() === $this) {
                    $achat->setPersonne(null);
                }
            }

            return $this;
        }

        public function __toString(): string
        {
            return $this->email;
        }

        public function getMatricule(): ?string
        {
            return $this->matricule;
        }

        public function setMatricule(?string $matricule): static
        {
            $this->matricule = $matricule;

            return $this;
        }

        public function isAccepted(): ?bool
        {
            return $this->accepted;
        }

        public function setAccepted(?bool $accepted): static
        {
            $this->accepted = $accepted;

            return $this;
        }

        /**
         * @return Collection<int, Comment>
         */
        public function getComments(): Collection
        {
            return $this->comments;
        }

        public function addComment(Comment $comment): static
        {
            if (!$this->comments->contains($comment)) {
                $this->comments->add($comment);
                $comment->setSender($this);
            }

            return $this;
        }

        public function removeComment(Comment $comment): static
        {
            if ($this->comments->removeElement($comment)) {
                // set the owning side to null (unless already changed)
                if ($comment->getSender() === $this) {
                    $comment->setSender(null);
                }
            }

            return $this;
        }

        /**
         * @return Collection<int, Notification>
         */
        public function getNotifications(): Collection
        {
            return $this->notifications;
        }

        public function addNotification(Notification $notification): static
        {
            if (!$this->notifications->contains($notification)) {
                $this->notifications->add($notification);
                $notification->setReciever($this);
            }

            return $this;
        }

        public function removeNotification(Notification $notification): static
        {
            if ($this->notifications->removeElement($notification)) {
                // set the owning side to null (unless already changed)
                if ($notification->getReciever() === $this) {
                    $notification->setReciever(null);
                }
            }

            return $this;
        }

        public function getProfil(): ?Profil
        {
            return $this->profil;
        }

        public function setProfil(Profil $profil): static
        {
            // set the owning side of the relation if necessary
            if ($profil->getCreatedby() !== $this) {
                $profil->setCreatedby($this);
            }

            $this->profil = $profil;

            return $this;
        }

        /**
         * @return Collection<int, FichePatient>
         */
        public function getFichePatients(): Collection
        {
            return $this->fichePatients;
        }

        public function addFichePatient(FichePatient $fichePatient): static
        {
            if (!$this->fichePatients->contains($fichePatient)) {
                $this->fichePatients->add($fichePatient);
                $fichePatient->setCreatedby($this);
            }

            return $this;
        }

        public function removeFichePatient(FichePatient $fichePatient): static
        {
            if ($this->fichePatients->removeElement($fichePatient)) {
                // set the owning side to null (unless already changed)
                if ($fichePatient->getCreatedby() === $this) {
                    $fichePatient->setCreatedby(null);
                }
            }

            return $this;
        }

        public function getTest(): ?string
        {
            return $this->test;
        }

        public function setTest(?string $test): static
        {
            $this->test = $test;

            return $this;
        }

}