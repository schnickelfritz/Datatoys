<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class ButtonsRow
{
    public int $offset = 3;

    public string $rowClass = '';
    public mixed $field;
    public string $icon = '';
    public string $label = '';
    public mixed $field2 = null;
    public string $icon2 = '';

    public string $cancelPath = '';
    public string $cancelLabel = '';

    public string $modalId = '';
    public string $modalLabel = '';
    public string $modalIcon = '';
    public string $modalType = '';
}