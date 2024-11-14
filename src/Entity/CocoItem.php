<?php

namespace App\Entity;

use App\Repository\CocoItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CocoItemRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_COCOID', fields: ['cocoId'])]
class CocoItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $cocoId = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\Column]
    private ?float $price = null;

    /**
     * @var Collection<int, CocoTicketPosition>
     */
    #[ORM\OneToMany(targetEntity: CocoTicketPosition::class, mappedBy: 'item')]
    private Collection $cocoTicketPositions;

    public function __construct()
    {
        $this->cocoTicketPositions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCocoId(): ?string
    {
        return $this->cocoId;
    }

    public function setCocoId(string $cocoId): static
    {
        $this->cocoId = $cocoId;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, CocoTicketPosition>
     */
    public function getCocoTicketPositions(): Collection
    {
        return $this->cocoTicketPositions;
    }

    public function addCocoTicketPosition(CocoTicketPosition $cocoTicketPosition): static
    {
        if (!$this->cocoTicketPositions->contains($cocoTicketPosition)) {
            $this->cocoTicketPositions->add($cocoTicketPosition);
            $cocoTicketPosition->setItem($this);
        }

        return $this;
    }

    public function removeCocoTicketPosition(CocoTicketPosition $cocoTicketPosition): static
    {
        if ($this->cocoTicketPositions->removeElement($cocoTicketPosition)) {
            // set the owning side to null (unless already changed)
            if ($cocoTicketPosition->getItem() === $this) {
                $cocoTicketPosition->setItem(null);
            }
        }

        return $this;
    }
}
