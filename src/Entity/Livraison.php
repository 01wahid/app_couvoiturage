<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LivraisonRepository")
 */
class Livraison
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'expéditeur ne doit pas être vide.")
     * @Assert\Length(max=255, maxMessage="Le nom de l'expéditeur ne peut pas dépasser 255 caractères.")
     */
    private $expediteur;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le destinataire ne doit pas être vide.")
     * @Assert\Length(max=255, maxMessage="Le nom du destinataire ne peut pas dépasser 255 caractères.")
     */
    private $destinataire;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'adresse de départ ne doit pas être vide.")
     * @Assert\Length(max=255, maxMessage="L'adresse de départ ne peut pas dépasser 255 caractères.")
     */
    private $adresseDepart;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'adresse d'arrivée ne doit pas être vide.")
     * @Assert\Length(max=255, maxMessage="L'adresse d'arrivée ne peut pas dépasser 255 caractères.")
     */
    private $adresseArrivee;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de livraison ne doit pas être vide.")
     * @Assert\GreaterThan("today", message="La date de livraison doit être dans le futur.")
     */
    private $dateLivraison;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'état de la livraison ne doit pas être vide.")
     * @Assert\Choice(choices={"En attente", "En cours", "Livrée"}, message="Choisissez un état valide.")
     */
    private $etat;

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExpediteur(): ?string
    {
        return $this->expediteur;
    }

    public function setExpediteur(string $expediteur): self
    {
        $this->expediteur = $expediteur;
        return $this;
    }

    public function getDestinataire(): ?string
    {
        return $this->destinataire;
    }

    public function setDestinataire(string $destinataire): self
    {
        $this->destinataire = $destinataire;
        return $this;
    }

    public function getAdresseDepart(): ?string
    {
        return $this->adresseDepart;
    }

    public function setAdresseDepart(string $adresseDepart): self
    {
        $this->adresseDepart = $adresseDepart;
        return $this;
    }

    public function getAdresseArrivee(): ?string
    {
        return $this->adresseArrivee;
    }

    public function setAdresseArrivee(string $adresseArrivee): self
    {
        $this->adresseArrivee = $adresseArrivee;
        return $this;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(\DateTimeInterface $dateLivraison): self
    {
        $this->dateLivraison = $dateLivraison;
        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;
        return $this;
    }
}