<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent]
class AccordionSingle
{
    #[ExposeInTemplate(name: 'id')]
    public ?string $id = null;
    public bool $expand = false;
    public bool $flush = false;

    public function getId(): string
    {
        return ($this->id === null) ? 'accordionsingle_' . uniqid() : $this->id;
    }

}