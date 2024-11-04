<?php

namespace App\Entity;

use App\Repository\GridfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GridfileRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_STOREDNAME', fields: ['storedName'])]
class Gridfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $originalName = null;

    #[ORM\Column(length: 255)]
    private ?string $storedName = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?int $width = null;

    #[ORM\Column(nullable: true)]
    private ?int $height = null;

    #[ORM\Column]
    private ?int $filesize = null;

    /**
     * @var Collection<int, Gridtable>
     */
    #[ORM\ManyToMany(targetEntity: Gridtable::class, inversedBy: 'gridfiles')]
    private Collection $gridtable;

    /**
     * @var Collection<int, Gridcell>
     */
    #[ORM\OneToMany(targetEntity: Gridcell::class, mappedBy: 'gridfile')]
    private Collection $gridcells;

    public function __construct()
    {
        $this->gridtable = new ArrayCollection();
        $this->gridcells = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): static
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getStoredName(): ?string
    {
        return $this->storedName;
    }

    public function setStoredName(string $storedName): static
    {
        $this->storedName = $storedName;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): static
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return Collection<int, Gridtable>
     */
    public function getGridtable(): Collection
    {
        return $this->gridtable;
    }

    public function addGridtable(Gridtable $gridtable): static
    {
        if (!$this->gridtable->contains($gridtable)) {
            $this->gridtable->add($gridtable);
        }

        return $this;
    }

    public function removeGridtable(Gridtable $gridtable): static
    {
        $this->gridtable->removeElement($gridtable);

        return $this;
    }

    public function getFilesize(): ?int
    {
        return $this->filesize;
    }

    public function setFilesize(int $filesize): static
    {
        $this->filesize = $filesize;

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
            $gridcell->setGridfile($this);
        }

        return $this;
    }

    public function removeGridcell(Gridcell $gridcell): static
    {
        if ($this->gridcells->removeElement($gridcell)) {
            // set the owning side to null (unless already changed)
            if ($gridcell->getGridfile() === $this) {
                $gridcell->setGridfile(null);
            }
        }

        return $this;
    }
}
