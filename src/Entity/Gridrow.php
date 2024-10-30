<?php

namespace App\Entity;

use App\Repository\GridrowRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GridrowRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_MASTERKEY', fields: ['masterkey'])]
class Gridrow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $masterkey = null;

    #[ORM\ManyToOne(inversedBy: 'gridrows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Gridpool $pool = null;

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

    public function getMasterkey(): ?string
    {
        return $this->masterkey;
    }

    public function setMasterkey(?string $masterkey): static
    {
        $this->masterkey = $masterkey;

        return $this;
    }

    public function getPool(): ?Gridpool
    {
        return $this->pool;
    }

    public function setPool(?Gridpool $pool): static
    {
        $this->pool = $pool;

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
}
