<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent]
class AccordionItem
{
    #[ExposeInTemplate(name: 'itemId')]
    public ?string $itemId = null;
    public bool $expand = false;
    public string $accordionId;
    public string $content = '';
    public string $button;

    public function getItemId(): string
    {
        return ($this->itemId === null) ? 'accordion_item_' . uniqid() : $this->itemId;
    }

}