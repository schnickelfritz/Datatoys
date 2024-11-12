<?php

namespace App\Service\Grid\GridValidate;

use App\Entity\Gridcell;
use App\Entity\Gridsetting;

final readonly class ValidateGridcell
{

    public function __construct(
        private ValidateValue $validateValue,
    )
    {
    }

    /**
     * @param Gridsetting[] $settings
     * @return string[]
     */
    public function validateCell(Gridcell $gridcell, array $settings): array
    {
        $fails = [];

        $value = $gridcell->getValue();

        foreach ($settings as $setting) {
            $valueFails = $this->validateValue->validateValue($value, $setting);
        }

        return $fails;
    }
}