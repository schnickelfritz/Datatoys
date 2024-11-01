<?php

namespace App\Entity;

use App\Repository\GridrowRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GridrowRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_TABLELINENUMBER', fields: ['gridtable', 'linenumber'])]
class Gridrow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $lineNumber = null;

    #[ORM\ManyToOne(inversedBy: 'gridrows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Gridtable $gridtable = null;

    /**
     * @var Collection<int, Gridcell>
     */
    #[ORM\OneToMany(targetEntity: Gridcell::class, mappedBy: 'y', orphanRemoval: true)]
    private Collection $gridcells;

    public function __construct()
    {
        $this->gridcells = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGridtable(): ?Gridtable
    {
        return $this->gridtable;
    }

    public function setGridtable(?Gridtable $table): static
    {
        $this->gridtable = $table;

        return $this;
    }

    /**
     * @return Collection<int, Gridcell>
     */
    public function getGridcells(): Collection
    {
        return $this->gridcells;
    }

    public function addGridcell(Gridcell $gridcell): static
    {
        if (!$this->gridcells->contains($gridcell)) {
            $this->gridcells->add($gridcell);
            $gridcell->setY($this);
        }

        return $this;
    }

    public function removeGridcell(Gridcell $gridcell): static
    {
        if ($this->gridcells->removeElement($gridcell)) {
            // set the owning side to null (unless already changed)
            if ($gridcell->getY() === $this) {
                $gridcell->setY(null);
            }
        }

        return $this;
    }

    public function getLineNumber(): ?int
    {
        return $this->lineNumber;
    }

    public function setLineNumber(int $lineNumber): static
    {
        $this->lineNumber = $lineNumber;

        return $this;
    }
}
