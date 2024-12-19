<?php

namespace App\Entity;

use App\Repository\ObjetPerduRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ObjetPerduRepository::class)]
class ObjetPerdu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $utilisateur;

    #[ORM\Column(type: 'string', length: 255)]
    private $objet;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'boolean')]
    private $isFound;

    #[ORM\Column(type: 'datetime')]
    private $datePerte;

    public function __construct()
    {
        $this->isFound = false;
        $this->datePerte = new \DateTime();
    }

    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsFound(): ?bool
    {
        return $this->isFound;
    }

    public function setIsFound(bool $isFound): self
    {
        $this->isFound = $isFound;

        return $this;
    }

    public function getDatePerte(): ?\DateTimeInterface
    {
        return $this->datePerte;
    }

    public function setDatePerte(\DateTimeInterface $datePerte): self
    {
        $this->datePerte = $datePerte;

        return $this;
    }


}