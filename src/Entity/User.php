<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Un compte est déjà associé à cette adresse e-mail.')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    /**
     * @var Collection<int, Client>
     */
    #[ORM\OneToMany(targetEntity: Client::class, mappedBy: 'createdBy')]
    private Collection $clients;

    #[ORM\Column(length: 7, nullable: true)]
    private ?string $mode = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatarUrl = null;

    #[ORM\Column(nullable: true)]
    private ?bool $lockAccess = null;

    /**
     * @var Collection<int, Annee>
     */
    #[ORM\OneToMany(targetEntity: Annee::class, mappedBy: 'createdBy')]
    private Collection $annees;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $googleId = null;

    /**
     * @var Collection<int, Projet>
     */
    #[ORM\OneToMany(targetEntity: Projet::class, mappedBy: 'createdBy')]
    private Collection $projets;

    /**
     * @var Collection<int, Projet>
     */
    #[ORM\OneToMany(targetEntity: Projet::class, mappedBy: 'affectedTo')]
    private Collection $affectedtoProjets;

    /**
     * @var Collection<int, Affectation>
     */
    #[ORM\OneToMany(targetEntity: Affectation::class, mappedBy: 'user')]
    private Collection $affectations;

    /**
     * @var Collection<int, Affectation>
     */
    #[ORM\OneToMany(targetEntity: Affectation::class, mappedBy: 'createdBy')]
    private Collection $createdByAffectations;

    /**
     * @var Collection<int, Role>
     */
    #[ORM\OneToMany(targetEntity: Role::class, mappedBy: 'createdBy')]
    private Collection $createdRoles;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->annees = new ArrayCollection();
        $this->projets = new ArrayCollection();
        $this->affectedtoProjets = new ArrayCollection();
        $this->affectations = new ArrayCollection();
        $this->createdByAffectations = new ArrayCollection();
        $this->createdRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setCreatedBy($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getCreatedBy() === $this) {
                $client->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(?string $mode): static
    {
        $this->mode = $mode;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(?string $avatarUrl): static
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    public function isLockAccess(): ?bool
    {
        return $this->lockAccess;
    }

    public function setLockAccess(?bool $lockAccess): static
    {
        $this->lockAccess = $lockAccess;

        return $this;
    }

    /**
     * @return Collection<int, Annee>
     */
    public function getAnnees(): Collection
    {
        return $this->annees;
    }

    public function addAnnee(Annee $annee): static
    {
        if (!$this->annees->contains($annee)) {
            $this->annees->add($annee);
            $annee->setCreatedBy($this);
        }

        return $this;
    }

    public function removeAnnee(Annee $annee): static
    {
        if ($this->annees->removeElement($annee)) {
            // set the owning side to null (unless already changed)
            if ($annee->getCreatedBy() === $this) {
                $annee->setCreatedBy(null);
            }
        }
        return $this;
    }

    public function __toString()
    {
        return $this->getName() ?? '';
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): static
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * @return Collection<int, Projet>
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function addProjet(Projet $projet): static
    {
        if (!$this->projets->contains($projet)) {
            $this->projets->add($projet);
            $projet->setCreatedBy($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): static
    {
        if ($this->projets->removeElement($projet)) {
            // set the owning side to null (unless already changed)
            if ($projet->getCreatedBy() === $this) {
                $projet->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Projet>
     */
    public function getAffectedtoProjets(): Collection
    {
        return $this->affectedtoProjets;
    }

    public function addAffectedtoProjet(Projet $affectedtoProjet): static
    {
        if (!$this->affectedtoProjets->contains($affectedtoProjet)) {
            $this->affectedtoProjets->add($affectedtoProjet);
            $affectedtoProjet->setAffectedTo($this);
        }

        return $this;
    }

    public function removeAffectedtoProjet(Projet $affectedtoProjet): static
    {
        if ($this->affectedtoProjets->removeElement($affectedtoProjet)) {
            // set the owning side to null (unless already changed)
            if ($affectedtoProjet->getAffectedTo() === $this) {
                $affectedtoProjet->setAffectedTo(null);
            }
        }

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
            $affectation->setUser($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): static
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getUser() === $this) {
                $affectation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Affectation>
     */
    public function getCreatedByAffectations(): Collection
    {
        return $this->createdByAffectations;
    }

    public function addCreatedByAffectation(Affectation $createdByAffectation): static
    {
        if (!$this->createdByAffectations->contains($createdByAffectation)) {
            $this->createdByAffectations->add($createdByAffectation);
            $createdByAffectation->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedByAffectation(Affectation $createdByAffectation): static
    {
        if ($this->createdByAffectations->removeElement($createdByAffectation)) {
            // set the owning side to null (unless already changed)
            if ($createdByAffectation->getCreatedBy() === $this) {
                $createdByAffectation->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Role>
     */
    public function getCreatedRoles(): Collection
    {
        return $this->createdRoles;
    }

    public function addCreatedRole(Role $createdRole): static
    {
        if (!$this->createdRoles->contains($createdRole)) {
            $this->createdRoles->add($createdRole);
            $createdRole->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedRole(Role $createdRole): static
    {
        if ($this->createdRoles->removeElement($createdRole)) {
            // set the owning side to null (unless already changed)
            if ($createdRole->getCreatedBy() === $this) {
                $createdRole->setCreatedBy(null);
            }
        }

        return $this;
    }
}
