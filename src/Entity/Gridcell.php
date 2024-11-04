<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GridcellRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GridcellRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_ROWCOL', fields: ['gridrow', 'gridcol'])]
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
    private Gridcol $gridcol;

    #[ORM\ManyToOne(inversedBy: 'gridcells')]
    #[ORM\JoinColumn(nullable: false)]
    private Gridrow $gridrow;

    #[ORM\ManyToOne(inversedBy: 'gridcells')]
    private ?Gridfile $gridfile = null;

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

    public function getGridcol(): Gridcol
    {
        return $this->gridcol;
    }

    public function setGridcol(Gridcol $gridcol): static
    {
        $this->gridcol = $gridcol;

        return $this;
    }

    public function getGridrow(): Gridrow
    {
        return $this->gridrow;
    }

    public function setGridrow(Gridrow $gridrow): static
    {
        $this->gridrow = $gridrow;

        return $this;
    }

    public function getGridfile(): ?Gridfile
    {
        return $this->gridfile;
    }

    public function setGridfile(?Gridfile $gridfile): static
    {
        $this->gridfile = $gridfile;

        return $this;
    }
}
