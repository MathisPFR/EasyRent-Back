<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use App\Repository\DocumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
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
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, columnDefinition: "MEDIUMTEXT")]
    private ?string $documentBlob = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateAjout = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    private ?Locataire $locataire = null;

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

    public function getDocumentBlob(): ?string
    {
        return $this->documentBlob;
    }

    public function setDocumentBlob(?string $documentBlob): static
    {
        $this->documentBlob = $documentBlob;
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
