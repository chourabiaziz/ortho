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

  
 
 
    #[ORM\Column(length: 50 ,nullable: true)]
    private ?string $nomP = null;
 
 
    #[ORM\Column(type: Types::DATE_MUTABLE ,nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'lettreSuivies')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $ortho = null;

    #[ORM\ManyToOne(inversedBy: 'lettrelists')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $createdby = null;

    #[ORM\Column]
    private ?int $nimage = null;

    #[ORM\Column(length: 99999, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT , nullable: true)]
    private ?string $p1 = null;

    #[ORM\Column(type: Types::TEXT , nullable: true)]
    private ?string $p2 = null;

    #[ORM\Column(type: Types::TEXT , nullable: true)]
    private ?string $p3 = null;

    #[ORM\Column(type: Types::TEXT , nullable: true)]
    private ?string $p4 = null;

    #[ORM\Column(type: Types::TEXT , nullable: true)]
    private ?string $p5 = null;

    #[ORM\Column(type: Types::TEXT , nullable: true)]
    private ?string $p6 = null;

    #[ORM\Column(type: Types::TEXT , nullable: true)]
    private ?string $p7 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

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

    public function getNimage(): ?int
    {
        return $this->nimage;
    }

    public function setNimage(?int $nimage): static
    {
        $this->nimage = $nimage;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getP1(): ?string
    {
        return $this->p1;
    }

    public function setP1(?string $p1): static
    {
        $this->p1 = $p1;

        return $this;
    }

    public function getP2(): ?string
    {
        return $this->p2;
    }

    public function setP2(?string $p2): static
    {
        $this->p2 = $p2;

        return $this;
    }

    public function getP3(): ?string
    {
        return $this->p3;
    }

    public function setP3(?string $p3): static
    {
        $this->p3 = $p3;

        return $this;
    }

    public function getP4(): ?string
    {
        return $this->p4;
    }

    public function setP4(string $p4): static
    {
        $this->p4 = $p4;

        return $this;
    }

    public function getP5(): ?string
    {
        return $this->p5;
    }

    public function setP5(?string $p5): static
    {
        $this->p5 = $p5;

        return $this;
    }

    public function getP6(): ?string
    {
        return $this->p6;
    }

    public function setP6(?string $p6): static
    {
        $this->p6 = $p6;

        return $this;
    }

    public function getP7(): ?string
    {
        return $this->p7;
    }

    public function setP7(string $p7): static
    {
        $this->p7 = $p7;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
