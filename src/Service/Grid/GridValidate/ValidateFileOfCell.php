<?php

namespace App\Service\Grid\GridValidate;

use App\Entity\Gridcell;
use App\Entity\Gridfile;
use App\Entity\Gridsetting;

final readonly class ValidateFileOfCell
{

    public function __construct(
        private ValidateFile $validateFile,
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

        $gridfile = $gridcell->getGridfile();

        $isFileRelated = false;
        foreach ($settings as $setting) {
            $key = $setting->getSettingKey();
            if ($key === 'FILENAME') {
                if (!$gridfile instanceof Gridfile) {
                    $fails[] = 'validate.fail.no_file_mapped';
                    return $fails;
                }
                $isFileRelated = true;
                break;
            }
        }

        if (!$isFileRelated) {
            return $fails;
        }

        foreach ($settings as $setting) {
            $valueFails = $this->validateFile->validateFile($gridfile, $setting);
        }

        return $fails;
    }

}