<?php

namespace App\Entity;

use App\Repository\LettreSuiviesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LettreSuiviesRepository::class)]
class LettreSuivies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

  
 

    #[ORM\Column(length: 50)]
    private ?string $nomP = null;
 
 
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'lettreSuivies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $ortho = null;

    #[ORM\ManyToOne(inversedBy: 'lettrelists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdby = null;

    public function getId(): ?int
    {
        return $this->id;
    }

   
   

    public function getNomP(): ?string
    {
        return $this->nomP;
    }

    public function setNomP(string $nomP): static
    {
        $this->nomP = $nomP;

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

    public function getOrtho(): ?User
    {
        return $this->ortho;
    }

    public function setOrtho(?User $ortho): static
    {
        $this->ortho = $ortho;

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
}
