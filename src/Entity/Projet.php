<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Enum\StatutProjet;
use App\Enum\TypePrestation;
use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Projet
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'projets')]
    private ?User $createdBy = null;

    #[ORM\ManyToOne(inversedBy: 'projets')]
    private ?Client $client = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(enumType: TypePrestation::class)]
    private ?TypePrestation $typePrestation = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $startAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $endAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $budget = null;

    #[ORM\ManyToOne(inversedBy: 'affectedtoProjets')]
    private ?User $affectedTo = null;

    #[ORM\Column(nullable: true, enumType: StatutProjet::class)]
    private ?StatutProjet $statut = null;

    #[ORM\ManyToOne(inversedBy: 'projets')]
    private ?Annee $annee = null;

    /**
     * @var Collection<int, Affectation>
     */
    #[ORM\OneToMany(targetEntity: Affectation::class, mappedBy: 'projet')]
    private Collection $affectations;

    public function __construct()
    {
        $this->affectations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getTypePrestation(): ?TypePrestation
    {
        return $this->typePrestation;
    }

    public function setTypePrestation(TypePrestation $typePrestation): static
    {
        $this->typePrestation = $typePrestation;

        return $this;
    }

    public function getStartAt(): ?\DateTime
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTime $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTime
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTime $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getBudget(): ?int
    {
        return $this->budget;
    }

    public function setBudget(?int $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    public function __toString()
    {
        return $this->getName() ?? '';
    }

    public function getAffectedTo(): ?User
    {
        return $this->affectedTo;
    }

    public function setAffectedTo(?User $affectedTo): static
    {
        $this->affectedTo = $affectedTo;

        return $this;
    }

    public function getStatut(): ?StatutProjet
    {
        return $this->statut;
    }

    public function setStatut(?StatutProjet $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getAnnee(): ?Annee
    {
        return $this->annee;
    }

    public function setAnnee(?Annee $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * @return Collection<int, Affectation>
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectation $affectation): static
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations->add($affectation);
            $affectation->setProjet($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): static
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getProjet() === $this) {
                $affectation->setProjet(null);
            }
        }

        return $this;
    }
}
