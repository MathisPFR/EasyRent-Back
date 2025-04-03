<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use App\Repository\PaiementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_USER')"),
        new Post(
            security: "is_granted('ROLE_USER')",
            securityPostDenormalize: "is_granted('ROLE_USER') and object.getLocataire() !== null and object.getLocataire().getBiens().getUsers() == user",
            securityPostDenormalizeMessage: "Vous ne pouvez pas créer un locataire pour un bien qui ne vous appartient pas."
        ),
        new Get(security: "is_granted('ROLE_USER') and object.getLocataire().getBiens().getUsers() == user"),
        new Put(security: "is_granted('ROLE_USER') and object.getLocataire().getBiens().getUsers() == user"),
        new Patch(security: "is_granted('ROLE_USER') and object.getLocataire().getBiens().getUsers() == user"),
        new Delete(security: "is_granted('ROLE_USER') and object.getLocataire().getBiens().getUsers() == user"),
    ],
)]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePaiement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $moisConcerne = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'paiements')]
    private ?Locataire $locataire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDatePaiement(): ?\DateTimeInterface
    {
        return $this->datePaiement;
    }

    public function setDatePaiement(?\DateTimeInterface $datePaiement): static
    {
        $this->datePaiement = $datePaiement;

        return $this;
    }

    public function getMoisConcerne(): ?string
    {
        return $this->moisConcerne;
    }

    public function setMoisConcerne(?string $moisConcerne): static
    {
        $this->moisConcerne = $moisConcerne;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getLocataire(): ?Locataire
    {
        return $this->locataire;
    }

    public function setLocataire(?Locataire $locataire): static
    {
        $this->locataire = $locataire;

        return $this;
    }
}
