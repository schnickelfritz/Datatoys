<?php

declare(strict_types=1);

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
    private string $name;

    /**
     * @var Collection<int, Gridcell>
     */
    #[ORM\OneToMany(targetEntity: Gridcell::class, mappedBy: 'gridcol', orphanRemoval: true)]
    private Collection $gridcells;

    /**
     * @var Collection<int, GridscopeCol>
     */
    #[ORM\OneToMany(targetEntity: GridscopeCol::class, mappedBy: 'col', orphanRemoval: true)]
    private Collection $gridscopeCols;

    /**
     * @var Collection<int, Gridsetting>
     */
    #[ORM\OneToMany(targetEntity: Gridsetting::class, mappedBy: 'gridcol')]
    private Collection $gridsettings;

    public function __construct()
    {
        $this->gridcells = new ArrayCollection();
        $this->gridscopeCols = new ArrayCollection();
        $this->gridsettings = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Gridcell>
     */
    public function getGridcells(): Collection
    {
        return $this->gridcells;
    }

    /**
     * @return Collection<int, GridscopeCol>
     */
    public function getGridscopeCols(): Collection
    {
        return $this->gridscopeCols;
    }

    public function getChecksum(): string
    {
        $allValues = [
            $this->getName(),
        ];
        $serializedValues = serialize($allValues);

        return md5($serializedValues);
    }

    /**
     * @return Collection<int, Gridsetting>
     */
    public function getGridsettings(): Collection
    {
        return $this->gridsettings;
    }

    public function addGridsetting(Gridsetting $gridsetting): static
    {
        if (!$this->gridsettings->contains($gridsetting)) {
            $this->gridsettings->add($gridsetting);
            $gridsetting->setGridcol($this);
        }

        return $this;
    }

    public function removeGridsetting(Gridsetting $gridsetting): static
    {
        if ($this->gridsettings->removeElement($gridsetting)) {
            // set the owning side to null (unless already changed)
            if ($gridsetting->getGridcol() === $this) {
                $gridsetting->setGridcol(null);
            }
        }

        return $this;
    }

}
