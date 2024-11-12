<?php

namespace App\Service\Grid\GridValidate;

use App\Entity\Gridfile;
use App\Entity\Gridsetting;
use App\Trait\StringExplodeTrait;

final readonly class ValidateFile
{

    use StringExplodeTrait;

    public function __construct(

    )
    {
    }

    public function validateFile(Gridfile $gridfile, Gridsetting $setting): ?string
    {
        $key = $setting->getSettingKey();
        $param = $setting->getParameter();

        return match ($key) {
            'FILETYPES' => $this->validateFiletypes($gridfile, $param),
            default => null,
        };
    }

    private function validateFiletypes(Gridfile $gridfile, ?string $param): ?string
    {
        if ($this->invalidEmptyParam($param)) {
            return 'gridvalidate.fail.internal_param_missing FILETYPES';
        }
        $filetype = $gridfile->getType();
        $allowedTypes = $this->explode($param, ['|']);
        if (!in_array($filetype, $allowedTypes, true)) {
            return 'gridvalidate.fail.type_not_allowed';
        }
        
        return null;
    }

    private function invalidEmptyParam(?string $param): bool
    {
        return ($param === null || $param === '');
    }
}