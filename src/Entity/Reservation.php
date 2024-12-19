<?php

namespace App\Entity;

use App\Enum\Statut;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idReservation', type: 'integer')]
    private int $idReservation;

    #[ORM\Column(type: 'string', enumType: Statut::class)]
    private Statut $statut;

    #[ORM\Column(type: 'string', length: 255)]
    private string $nomPassager;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'trajet_id', referencedColumnName: 'idTrajet', nullable: false)]
    private ?Trajet $trajet = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getIdReservation(): int
    {
        return $this->idReservation;
    }

    public function getStatut(): Statut
    {
        return $this->statut;
    }

    public function setStatut(Statut $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getNomPassager(): string
    {
        return $this->nomPassager;
    }

    public function setNomPassager(string $nomPassager): self
    {
        $this->nomPassager = $nomPassager;
        return $this;
    }

    public function getTrajet(): ?Trajet
    {
        return $this->trajet;
    }

    public function setTrajet(?Trajet $trajet): self
    {
        $this->trajet = $trajet;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
    public function getStatutValue(): string
{
    return $this->statut->value;
}

}
