<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class NavItem
{
    public string $route = '';
    public string $routePrefix = '';
    public string $icon = '';
    public string $label = '';
    public string $labelDirect = '';
    public mixed $path = '';
    public string $class = '';
}
