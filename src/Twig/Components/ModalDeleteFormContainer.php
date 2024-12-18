<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class ModalDeleteFormContainer
{
    public string $id;
    public string $title;
    public string $content;
    public string $closed = 'Cancel';

    public string $path;
    public string $formId = 'delete_form';
    public string $formName = 'delete_form';
    public int $deleteId = -1;
    public string $buttonLabel = 'modal.delete.submitbutton';
}
