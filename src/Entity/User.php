<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
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
    private ?string $nom = null;

    #[ORM\OneToMany(targetEntity: LettreSuivies::class, mappedBy: 'ortho')]
    private Collection $lettreSuivies;

    #[ORM\OneToMany(targetEntity: LettreSuivies::class, mappedBy: 'createdby')]
    private Collection $lettrelists;

    public function __construct()
    {
        $this->lettreSuivies = new ArrayCollection();
        $this->lettrelists = new ArrayCollection();
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

    /**
     * @return Collection<int, LettreSuivies>
     */
    public function getLettreSuivies(): Collection
    {
        return $this->lettreSuivies;
    }

    public function addLettreSuivy(LettreSuivies $lettreSuivy): static
    {
        if (!$this->lettreSuivies->contains($lettreSuivy)) {
            $this->lettreSuivies->add($lettreSuivy);
            $lettreSuivy->setOrtho($this);
        }

        return $this;
    }

    public function removeLettreSuivy(LettreSuivies $lettreSuivy): static
    {
        if ($this->lettreSuivies->removeElement($lettreSuivy)) {
            // set the owning side to null (unless already changed)
            if ($lettreSuivy->getOrtho() === $this) {
                $lettreSuivy->setOrtho(null);
            }
        }

        return $this;
    }

    
        public function __toString(){
        return $this->getNom()." | ".$this->getEmail() ;
        }

        /**
         * @return Collection<int, LettreSuivies>
         */
        public function getLettrelists(): Collection
        {
            return $this->lettrelists;
        }

        public function addLettrelist(LettreSuivies $lettrelist): static
        {
            if (!$this->lettrelists->contains($lettrelist)) {
                $this->lettrelists->add($lettrelist);
                $lettrelist->setCreatedby($this);
            }

            return $this;
        }

        public function removeLettrelist(LettreSuivies $lettrelist): static
        {
            if ($this->lettrelists->removeElement($lettrelist)) {
                // set the owning side to null (unless already changed)
                if ($lettrelist->getCreatedby() === $this) {
                    $lettrelist->setCreatedby(null);
                }
            }

            return $this;
        }



}
