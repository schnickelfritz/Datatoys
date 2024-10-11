<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Primarybox
{
    public string $class = "app-primarybox";
    public string $content;
}