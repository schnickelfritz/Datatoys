<?php

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
    public int $deleteId;
    public string $buttonLabel = 'modal.delete.submitbutton';
}