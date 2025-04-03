<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use App\Controller\BienController;
use App\Controller\BienPatchController;
use App\Repository\BienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BienRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_USER')"),
        new GetCollection(
            security: "is_granted('ROLE_USER')",
            uriTemplate: '/biens/recherche/{id}',
            controller: BienController::class,
            name: 'get_recherche_adresse',
        ),
        new Post(security: "is_granted('ROLE_USER')"),
        new Get(security: "is_granted('ROLE_USER') and object.getUsers() == user"),
        new Put(security: "is_granted('ROLE_USER') and object.getUsers() == user"),
        new Patch(
            security: "is_granted('ROLE_USER') and object.getUsers() == user",
            controller: BienPatchController::class,
            name: 'app_bien_patch',
        ),
        new Delete(security: "is_granted('ROLE_USER') and object.getUsers() == user"),
    ],
    normalizationContext: ['groups' => ['bien:read']],
)]
class Bien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['bien:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['bien:read'])]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Groups(['bien:read'])]
    private ?string $adresse = null;

    #[ORM\Column]
    #[Groups(['bien:read'])]
    private ?float $surface = null;

    #[ORM\Column(length: 255)]
    #[Groups(['bien:read'])]
    private ?string $type = null;

    #[ORM\Column]
    #[Groups(['bien:read'])]
    private ?float $loyer = null;

    #[ORM\ManyToOne(inversedBy: 'biens')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['bien:read'])]
    private ?User $users = null;

    /**
     * @var Collection<int, Locataire>
     */
    #[ORM\OneToMany(targetEntity: Locataire::class, mappedBy: 'biens')]
    #[Groups(['bien:read'])]
    private Collection $locataires;

    #[ORM\Column]
    #[Groups(['bien:read'])]
    private ?bool $actif = null;

    public function __construct()
    {
        $this->locataires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getSurface(): ?float
    {
        return $this->surface;
    }

    public function setSurface(float $surface): static
    {
        $this->surface = $surface;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getLoyer(): ?float
    {
        return $this->loyer;
    }

    public function setLoyer(float $loyer): static
    {
        $this->loyer = $loyer;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): static
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection<int, Locataire>
     */
    public function getLocataires(): Collection
    {
        return $this->locataires;
    }

    public function addLocataire(Locataire $locataire): static
    {
        if (!$this->locataires->contains($locataire)) {
            $this->locataires->add($locataire);
            $locataire->setBiens($this);
        }

        return $this;
    }

    public function removeLocataire(Locataire $locataire): static
    {
        if ($this->locataires->removeElement($locataire)) {
            // set the owning side to null (unless already changed)
            if ($locataire->getBiens() === $this) {
                $locataire->setBiens(null);
            }
        }

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }
}
