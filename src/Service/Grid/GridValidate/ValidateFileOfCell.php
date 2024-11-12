<?php

namespace App\Service\Grid\GridValidate;

use App\Entity\Gridcell;
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
    public function val(Gridcell $gridcell, array $settings): array
    {
        $fails = [];

        $gridfile = $gridcell->getGridfile();

        $isFileRelated = false;
        foreach ($settings as $setting) {
            $key = $setting->getSettingKey();
            if ($key === 'TYPE_FILENAME') {
                if ($gridfile === null) {
                    $fails[] = 'no_file_mapped';
                    return $fails;
                }
                $isFileRelated = true;
                break;
            }
        }

        if (!$isFileRelated || $gridfile === null) {
            return $fails;
        }

        foreach ($settings as $setting) {
            $fileFail = $this->validateFile->validateFile($gridfile, $setting);
            if ($fileFail !== null) {
                $fails[] = $fileFail;
            }
        }

        return $fails;
    }

}