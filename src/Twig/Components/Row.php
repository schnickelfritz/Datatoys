<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Row
{
    public string $content = '';
    public string $labelIcon = '';
    public string $label = '';
    public string $labelId = '';
    public string $labelAdditional = '';
    public int $labelWidth = 3;
    public string $labelClass;
    public bool $makePrependSpace = false;
    public string $rowClass = 'app-row';
    public string $append = '';
    public int $appendWidth = 0;
    public string $prepend = '';
    public int $prependWidth = 0;
}
