<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\ManyToOne(inversedBy: 'userId')]
    private ?Task $task = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Projekt::class)]
    private Collection $projekts;

    public function __construct()
    {
        $this->projekts = new ArrayCollection();
    }

    // Getter und Setter

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): static
    {
        $this->task = $task;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return Collection<int, Projekt>
     */
    public function getProjekts(): Collection
    {
        return $this->projekts;
    }

    public function addProjekt(Projekt $projekt): self
    {
        if (!$this->projekts->contains($projekt)) {
            $this->projekts->add($projekt);
            $projekt->setUser($this);
        }

        return $this;
    }

    public function removeProjekt(Projekt $projekt): self
    {
        if ($this->projekts->removeElement($projekt)) {
            // set the owning side to null (unless already changed)
            if ($projekt->getUser() === $this) {
                $projekt->setUser(null);
            }
        }

        return $this;
    }

    /**
     * Liefert den eindeutigen Identifikator des Nutzers.
     * In modernen Symfony-Versionen ersetzt diese Methode getUsername().
     */
    public function getUserIdentifier(): string
    {
        return $this->email ?? $this->username;
    }

    /**
     * Gibt ein Array der Rollen zurück, die diesem Nutzer zugeordnet sind.
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * Löscht temporäre, sensible Daten.
     */
    public function eraseCredentials(): void
    {
    }
}
