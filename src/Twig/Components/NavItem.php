<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class NavItem
{
    public string $path;
    public string $icon = '';
    public string $label;

}