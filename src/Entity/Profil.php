<?php

namespace App\Entity;

use App\Repository\ProfilRepository;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfilRepository::class)]
class Profil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Champ obligatoir")]

    private ?string $emplacement = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Champ obligatoir")]

    private ?string $diplome = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Champ obligatoir")]

    private ?string $faculte = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Champ obligatoir")]
    #[Assert\NotNull(message: "Champ obligatoir")]

    private ?string $specialite = null;

    #[ORM\OneToOne(inversedBy: 'profil', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdby = null;
 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(?string $emplacement): static
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    public function getDiplome(): ?string
    {
        return $this->diplome;
    }

    public function setDiplome(?string $diplome): static
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getFaculte(): ?string
    {
        return $this->faculte;
    }

    public function setFaculte(?string $faculte): static
    {
        $this->faculte = $faculte;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getCreatedby(): ?User
    {
        return $this->createdby;
    }

    public function setCreatedby(User $createdby): static
    {
        $this->createdby = $createdby;

        return $this;
    }
 
}
