<?php

namespace App\Entity;

use App\Repository\GridpoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: GridpoolRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_NAMESCOPE', fields: ['name', 'scope'])]
#[UniqueEntity(fields: ['name'], message: 'flash.fail.taken')]
class Gridpool
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'gridpools'), ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Gridscope $scope = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Gridrow>
     */
    #[ORM\OneToMany(targetEntity: Gridrow::class, mappedBy: 'pool', orphanRemoval: true)]
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
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
            $gridrow->setPool($this);
        }

        return $this;
    }

    public function removeGridrow(Gridrow $gridrow): static
    {
        if ($this->gridrows->removeElement($gridrow)) {
            // set the owning side to null (unless already changed)
            if ($gridrow->getPool() === $this) {
                $gridrow->setPool(null);
            }
        }

        return $this;
    }

}
