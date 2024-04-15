<?php

namespace App\Entity;

use App\Repository\AchatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AchatRepository::class)]
class Achat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'achats')]
    private ?Abonnement $abnonnement = null;

    #[ORM\ManyToOne(inversedBy: 'achats')]
    private ?User $personne = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Datefin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAbnonnement(): ?Abonnement
    {
        return $this->abnonnement;
    }

    public function setAbnonnement(?Abonnement $abnonnement): static
    {
        $this->abnonnement = $abnonnement;

        return $this;
    }

    public function getPersonne(): ?User
    {
        return $this->personne;
    }

    public function setPersonne(?User $personne): static
    {
        $this->personne = $personne;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->Datefin;
    }

    public function setDatefin(\DateTimeInterface $Datefin): static
    {
        $this->Datefin = $Datefin;

        return $this;
    }
}
