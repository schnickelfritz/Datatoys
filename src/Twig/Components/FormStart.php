<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class FormStart
{
    public mixed $form;
    public string $gridThreshold = 'lg';
    public int $labelWidth = 3;
    public string $rowClass = 'app-row';
}
