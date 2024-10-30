<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class ModalButton
{
    public string $type = 'primary';
    public string $id;
    public string $icon = '';
    public string $label;
}
