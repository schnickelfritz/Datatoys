<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\RoleEnum;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAILNAME', fields: ['email', 'name'])]
#[UniqueEntity(fields: ['email'], message: 'flash.fail.taken')]
#[UniqueEntity(fields: ['name'], message: 'flash.fail.taken')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private string $email;

    /** @var array<string>|null */
    #[ORM\Column(nullable: true)]
    private ?array $roles = null;

    #[ORM\Column]
    private string $password;

    #[ORM\Column(length: 60)]
    private string $name;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    /**
     * @var Collection<int, Workday>
     */
    #[ORM\OneToMany(targetEntity: Workday::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $workdays;

    public function __construct()
    {
        $this->workdays = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
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

    /** @return string[] */
    public function getRoles(): array
    {
        $roles = $this->roles;
        if (empty($roles)) {
            return [];
        }

        return array_unique($roles);
    }

    /** @return string[] */
    public function getRolesLabels(): array
    {
        $roles = $this->roles;
        if (empty($roles)) {
            return [];
        }
        $labels = [];
        foreach ($roles as $role) {
            $roleEnum = RoleEnum::tryFrom($role);
            if ($roleEnum) {
                $labels[] = $roleEnum->label();
            }
        }

        return array_unique($labels);
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
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Workday>
     */
    public function getWorkdays(): Collection
    {
        return $this->workdays;
    }

    public function addWorkday(Workday $workday): static
    {
        if (!$this->workdays->contains($workday)) {
            $this->workdays->add($workday);
            $workday->setUser($this);
        }

        return $this;
    }

    public function removeWorkday(Workday $workday): static
    {
        if ($this->workdays->removeElement($workday)) {
            // set the owning side to null (unless already changed)
            if ($workday->getUser() === $this) {
                $workday->setUser(null);
            }
        }

        return $this;
    }
}
