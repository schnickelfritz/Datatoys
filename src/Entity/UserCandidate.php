<?php

namespace App\Entity;

use App\Enum\RoleEnum;
use App\Repository\UserCandidateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserCandidateRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email', 'name'])]
#[UniqueEntity(fields: ['email'], message: 'Email already in use.')]
#[UniqueEntity(fields: ['name'], message: 'Name already in use.')]

class UserCandidate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private string $name;

    #[ORM\Column(length: 180)]
    private string $email;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column(nullable: true)]
    private ?array $roles = null;

    #[ORM\Column(nullable: true)]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $inviteSentAt = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
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

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getInviteSentAt(): ?\DateTimeImmutable
    {
        return $this->inviteSentAt;
    }

    public function setInviteSentAt(?\DateTimeImmutable $inviteSentAt): static
    {
        $this->inviteSentAt = $inviteSentAt;

        return $this;
    }
}
