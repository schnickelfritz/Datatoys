<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Box
{
    public string $class = '';
    public string $content;
    public bool $stickyTop = false;
}
