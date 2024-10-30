<?php

declare(strict_types=1);

namespace App\Trait;

use InvalidArgumentException;
use Symfony\Component\Form\FormInterface;

use function is_string;

trait FormStringValueTrait
{
    protected function formStringValue(FormInterface $form, string $fieldname): string
    {
        $value = $form->get($fieldname)->getData();
        if ($value === null) {
            return '';
        }
        if (!is_string($value)) {
            throw new InvalidArgumentException('String expected for ' . $fieldname);
        }

        return $value;
    }
}
