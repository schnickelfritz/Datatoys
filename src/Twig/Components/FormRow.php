<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class FormRow
{

    public mixed $field;
    public string $rowClass = "app-row";
    public string $gridThreshold = 'lg';
    public int $labelWidth = 3;
}