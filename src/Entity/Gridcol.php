<?php

namespace App\Entity;

use App\Repository\GridcolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GridcolRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_NAME', fields: ['name'])]
class Gridcol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Gridcell>
     */
    #[ORM\OneToMany(targetEntity: Gridcell::class, mappedBy: 'x', orphanRemoval: true)]
    private Collection $gridcells;

    /**
     * @var Collection<int, GridscopeCol>
     */
    #[ORM\OneToMany(targetEntity: GridscopeCol::class, mappedBy: 'col', orphanRemoval: true)]
    private Collection $gridscopeCols;

    public function __construct()
    {
        $this->gridcells = new ArrayCollection();
        $this->gridscopeCols = new ArrayCollection();
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
            $gridcell->setX($this);
        }

        return $this;
    }

    public function removeGridcell(Gridcell $gridcell): static
    {
        if ($this->gridcells->removeElement($gridcell)) {
            // set the owning side to null (unless already changed)
            if ($gridcell->getX() === $this) {
                $gridcell->setX(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GridscopeCol>
     */
    public function getGridscopeCols(): Collection
    {
        return $this->gridscopeCols;
    }

    public function addGridscopeCol(GridscopeCol $gridscopeCol): static
    {
        if (!$this->gridscopeCols->contains($gridscopeCol)) {
            $this->gridscopeCols->add($gridscopeCol);
            $gridscopeCol->setCol($this);
        }

        return $this;
    }

    public function removeGridscopeCol(GridscopeCol $gridscopeCol): static
    {
        if ($this->gridscopeCols->removeElement($gridscopeCol)) {
            // set the owning side to null (unless already changed)
            if ($gridscopeCol->getCol() === $this) {
                $gridscopeCol->setCol(null);
            }
        }

        return $this;
    }
}
