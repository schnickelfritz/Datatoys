<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class FormRow
{
    public mixed $field;
    public string $rowClass = 'app-row';
    public string $gridThreshold = 'lg';
    public int $labelWidth = 3;
    public bool $toggle = false;
    public bool $translate = false;
    public bool $plain = false;
}
