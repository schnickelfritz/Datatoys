<?php

namespace App\Entity;

use App\Repository\GridsettingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GridsettingRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_SCOPECOLKEY', fields: ['scope', 'gridcol', 'settingKey'])]
class Gridsetting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private string $settingKey;

    #[ORM\ManyToOne(inversedBy: 'gridsettings')]
    #[ORM\JoinColumn(nullable: false)]
    private Gridscope $scope;

    #[ORM\ManyToOne(inversedBy: 'gridsettings')]
    #[ORM\JoinColumn(nullable: false)]
    private Gridcol $gridcol;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $parameter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSettingKey(): string
    {
        return $this->settingKey;
    }

    public function setSettingKey(string $settingKey): static
    {
        $this->settingKey = $settingKey;

        return $this;
    }

    public function getScope(): Gridscope
    {
        return $this->scope;
    }

    public function setScope(Gridscope $scope): static
    {
        $this->scope = $scope;

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

    public function getParameter(): ?string
    {
        return $this->parameter;
    }

    public function setParameter(?string $parameter): static
    {
        $this->parameter = $parameter;

        return $this;
    }
}
