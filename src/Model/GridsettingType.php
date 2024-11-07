<?php

namespace App\Model;

class GridsettingType
{

    public function __construct(
        private string $name,
        private string $label,
        private string $argumentType,
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getArgumentType(): string
    {
        return $this->argumentType;
    }

    public function setArgumentType(string $argumentType): void
    {
        $this->argumentType = $argumentType;
    }

}