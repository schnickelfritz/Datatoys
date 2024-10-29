<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Table
{
    public string $content;
    public mixed $headers;
    public bool $stripes = true;
    public bool $small = false;
}