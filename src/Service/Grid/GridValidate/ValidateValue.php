<?php

namespace App\Service\Grid\GridValidate;

use App\Entity\Gridsetting;

final readonly class ValidateValue
{

    public function __construct()
    {
    }

    public function validateValue(?string $value, Gridsetting $setting): ?string
    {
        $key = $setting->getSettingKey();
        $param = $setting->getParameter();
        $value = $value === null ? '' : $value;

        return match ($key) {
            'REQUIRED' => $value != '' ? null : 'gridvalidate.fail.required',
            'TYPE_SINGLELINE' => str_contains($value, "\n") ? 'gridvalidate.fail.multiline' : null,
            default => null,
        };

    }
}