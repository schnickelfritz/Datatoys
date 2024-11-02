<?php

namespace App\Entity;

use App\Repository\GridscopeColRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GridscopeColRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_SCOPECOL', fields: ['scope', 'col'])]
class GridscopeCol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'gridscopeCols')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Gridscope $scope = null;

    #[ORM\ManyToOne(inversedBy: 'gridscopeCols')]
    #[ORM\JoinColumn(nullable: false)]
    private Gridcol $col;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCol(): Gridcol
    {
        return $this->col;
    }

    public function setCol(Gridcol $col): static
    {
        $this->col = $col;

        return $this;
    }
}
