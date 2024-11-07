<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GridscopeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: GridscopeRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_NAMEKEY', fields: ['name', 'scopeKey'])]
#[UniqueEntity(fields: ['name'], message: 'flash.fail.taken')]
#[UniqueEntity(fields: ['scopeKey'], message: 'flash.fail.taken')]
class Gridscope
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 100)]
    private ?string $scopeKey = null;

    /**
     * @var Collection<int, Gridtable>
     */
    #[ORM\OneToMany(targetEntity: Gridtable::class, mappedBy: 'scope')]
    private Collection $gridtables;

    /**
     * @var Collection<int, GridscopeCol>
     */
    #[ORM\OneToMany(targetEntity: GridscopeCol::class, mappedBy: 'scope', orphanRemoval: true)]
    private Collection $gridscopeCols;

    /**
     * @var Collection<int, Gridsetting>
     */
    #[ORM\OneToMany(targetEntity: Gridsetting::class, mappedBy: 'scope')]
    private Collection $gridsettings;

    public function __construct()
    {
        $this->gridtables = new ArrayCollection();
        $this->gridscopeCols = new ArrayCollection();
        $this->gridsettings = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getScopeKey(): ?string
    {
        return $this->scopeKey;
    }

    public function setScopeKey(string $scopeKey): static
    {
        $this->scopeKey = $scopeKey;

        return $this;
    }

    public function getChecksum(): string
    {
        $allValues = [
            $this->getName(),
            $this->getScopeKey(),
            $this->getDescription(),
        ];
        $serializedValues = serialize($allValues);

        return md5($serializedValues);
    }

    /**
     * @return Collection<int, Gridtable>
     */
    public function getGridtables(): Collection
    {
        return $this->gridtables;
    }

    public function addGridtable(Gridtable $gridtable): static
    {
        if (!$this->gridtables->contains($gridtable)) {
            $this->gridtables->add($gridtable);
            $gridtable->setScope($this);
        }

        return $this;
    }

    public function removeGridtable(Gridtable $gridtable): static
    {
        if ($this->gridtables->removeElement($gridtable)) {
            // set the owning side to null (unless already changed)
            if ($gridtable->getScope() === $this) {
                $gridtable->setScope(null);
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
            $gridscopeCol->setScope($this);
        }

        return $this;
    }

    public function removeGridscopeCol(GridscopeCol $gridscopeCol): static
    {
        if ($this->gridscopeCols->removeElement($gridscopeCol)) {
            // set the owning side to null (unless already changed)
            if ($gridscopeCol->getScope() === $this) {
                $gridscopeCol->setScope(null);
            }
        }

        return $this;
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
            $gridsetting->setScope($this);
        }

        return $this;
    }

    public function removeGridsetting(Gridsetting $gridsetting): static
    {
        if ($this->gridsettings->removeElement($gridsetting)) {
            // set the owning side to null (unless already changed)
            if ($gridsetting->getScope() === $this) {
                $gridsetting->setScope(null);
            }
        }

        return $this;
    }
}
