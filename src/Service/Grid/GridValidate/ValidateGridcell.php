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
    public function val(Gridcell $gridcell, array $settings): array
    {
        $fails = [];

        $value = $gridcell->getValue();

        foreach ($settings as $setting) {
            $valueFail = $this->validateValue->validateValue($value, $setting);
            if ($valueFail !== null) {
                $fails[] = $valueFail;
            }
        }

        return $fails;
    }
}