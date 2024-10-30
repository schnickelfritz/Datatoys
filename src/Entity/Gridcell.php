<?php

namespace App\Entity;

use App\Repository\GridcellRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GridcellRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_XY', fields: ['x', 'y'])]
class Gridcell
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'gridcells')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Gridcol $x = null;

    #[ORM\ManyToOne(inversedBy: 'gridcells')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Gridrow $y = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getX(): ?Gridcol
    {
        return $this->x;
    }

    public function setX(?Gridcol $x): static
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?Gridrow
    {
        return $this->y;
    }

    public function setY(?Gridrow $y): static
    {
        $this->y = $y;

        return $this;
    }
}
