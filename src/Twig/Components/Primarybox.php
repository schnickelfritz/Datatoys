<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Primarybox
{
    public string $class = 'app-primarybox';
    public string $parentClass = 'app-box';
    public string $content;
}
