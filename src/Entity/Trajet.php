<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Trajet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idTrajet', type: 'integer')]
    private int $idTrajet;

    #[ORM\Column(type: 'string', length: 255)]
    private string $nomConducteur;

    #[ORM\Column(type: 'string', length: 255)]
    private string $depart;

    #[ORM\Column(type: 'string', length: 255)]
    private string $destination;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $dateHeure;

    #[ORM\Column(type: 'integer')]
    private int $nombrePlaces;

    #[ORM\Column(type: 'float')]
    private float $contributionFrais;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'trajet', orphanRemoval: true)]
    private Collection $reservations;


    

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getIdTrajet(): int
    {
        return $this->idTrajet;
    }

    public function getNomConducteur(): string
    {
        return $this->nomConducteur;
    }

    public function setNomConducteur(string $nomConducteur): self
    {
        $this->nomConducteur = $nomConducteur;
        return $this;
    }

    public function getDepart(): string
    {
        return $this->depart;
    }

    public function setDepart(string $depart): self
    {
        $this->depart = $depart;
        return $this;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;
        return $this;
    }

    public function getDateHeure(): \DateTime
    {
        return $this->dateHeure;
    }

    public function setDateHeure(\DateTime $dateHeure): self
    {
        $this->dateHeure = $dateHeure;
        return $this;
    }

    public function getNombrePlaces(): int
    {
        return $this->nombrePlaces;
    }

    public function setNombrePlaces(int $nombrePlaces): self
    {
        $this->nombrePlaces = $nombrePlaces;
        return $this;
    }

    public function getContributionFrais(): float
    {
        return $this->contributionFrais;
    }

    public function setContributionFrais(float $contributionFrais): self
    {
        $this->contributionFrais = $contributionFrais;
        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setTrajet($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getTrajet() === $this) {
                $reservation->setTrajet(null);
            }
        }

        return $this;
    }
    
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;
    
    public function getUser(): ?User
{
    return $this->user;
}

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}

