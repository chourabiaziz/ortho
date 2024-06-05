<?php

namespace App\Entity;

use App\Repository\FichePatientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FichePatientRepository::class)]
class FichePatient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "champ obligatoir.")]

    private ?string $nom = null;

   

    #[ORM\Column(length: 50)]

    private ?string $sexe = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "champ obligatoir.")]
    #[Assert\NotNull(message: "champ obligatoir.")]
     

    private ?\DateTimeInterface $dateDeNaissance = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateAjout = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "champ obligatoir.")]

    private ?string $description = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "champ obligatoir.")]

    private ?string $ville = null;
    #[Assert\NotBlank(message: "champ obligatoir.")]


    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "champ obligatoir.")]

    private ?string $prenom = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "champ obligatoir.")]
    #[Assert\Regex(pattern:'/^\d{8}$/'    ,    message: "CIN doit comporte 8 caractére ")]
    private ?int $cin = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "champ obligatoir.")]
    #[Assert\Regex(pattern:'/^\d{8}$/'    ,    message: "Numero doit comporte 8 caractére")]
    private ?int $telephone = null;


    #[ORM\ManyToOne(inversedBy: 'fichePatients')]
    private ?User $createdby = null;

    public function getId(): ?int
    {
        return $this->id;
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

    

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->dateDeNaissance;
    }

    public function setDateDeNaissance(\DateTimeInterface $dateDeNaissance): static
    {
        $this->dateDeNaissance = $dateDeNaissance;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(\DateTimeInterface $dateAjout): static
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getCreatedby(): ?User
    {
        return $this->createdby;
    }

    public function setCreatedby(?User $createdby): static
    {
        $this->createdby = $createdby;

        return $this;
    }

    public function __toString()
    {
        return $this->getNom() .' '  .$this->getPrenom() ;    }

}