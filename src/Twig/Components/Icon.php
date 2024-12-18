<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Icon
{
    public string $name;
    public string $label = '';
    public bool $title = false;
    public bool $inline = false;
}
