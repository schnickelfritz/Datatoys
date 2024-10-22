<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class ModalContainer
{
    public string $id;
    public string $title;
    public string $content;
    public string $closed = 'Cancel';
}