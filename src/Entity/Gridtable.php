<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GridtableRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: GridtableRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_NAMESCOPE', fields: ['name', 'scope'])]
#[UniqueEntity(fields: ['name'], message: 'flash.fail.taken')]
class Gridtable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $category = null;

    #[ORM\Column(nullable: true)]
    private ?int $numberOfSources = null;

    #[ORM\Column(nullable: true)]
    private ?int $additionalExpense = null;

    #[ORM\ManyToOne(inversedBy: 'gridtables'), ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Gridscope $scope = null;

    /**
     * @var Collection<int, Gridrow>
     */
    #[ORM\OneToMany(targetEntity: Gridrow::class, mappedBy: 'gridtable', orphanRemoval: true)]
    private Collection $gridrows;

    public function __construct()
    {
        $this->gridrows = new ArrayCollection();
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

    public function getScope(): ?Gridscope
    {
        return $this->scope;
    }

    public function setScope(?Gridscope $scope): static
    {
        $this->scope = $scope;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getChecksum(): string
    {
        $allValues = [
            $this->getName(),
            $this->getScope(),
        ];
        $serializedValues = serialize($allValues);

        return md5($serializedValues);
    }

    /**
     * @return Collection<int, Gridrow>
     */
    public function getGridrows(): Collection
    {
        return $this->gridrows;
    }

    public function addGridrow(Gridrow $gridrow): static
    {
        if (!$this->gridrows->contains($gridrow)) {
            $this->gridrows->add($gridrow);
            $gridrow->setGridtable($this);
        }

        return $this;
    }

    public function removeGridrow(Gridrow $gridrow): static
    {
        if ($this->gridrows->removeElement($gridrow)) {
            // set the owning side to null (unless already changed)
            if ($gridrow->getGridtable() === $this) {
                $gridrow->setGridtable(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getNumberOfSources(): ?int
    {
        return $this->numberOfSources;
    }

    public function setNumberOfSources(?int $numberOfSources): static
    {
        $this->numberOfSources = $numberOfSources;

        return $this;
    }

    public function getAdditionalExpense(): ?int
    {
        return $this->additionalExpense;
    }

    public function setAdditionalExpense(?int $additionalExpense): static
    {
        $this->additionalExpense = $additionalExpense;

        return $this;
    }
}
