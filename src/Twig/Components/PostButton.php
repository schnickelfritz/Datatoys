<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent]
class PostButton
{
    #[ExposeInTemplate(name: 'formId')]
    private ?string $formId = null;
    public string $path;
    public int $id = -1;
    public string $label = '';
    public string $title = '';
    public string $icon = '';

    public function getFormId(): string
    {
        return ($this->formId === null) ? 'postbutton_' . uniqid() : $this->formId;
    }
}
