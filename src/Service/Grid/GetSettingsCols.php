<?php

namespace App\Service\Grid;

use App\Entity\Gridcol;
use App\Entity\Gridscope;
use App\Repository\GridsettingRepository;
use App\Repository\GridsettingTypeRepository;

final readonly class GetSettingsCols
{

    public function __construct(
        private GridsettingRepository $gridsettingRepository,
        private GridsettingTypeRepository $gridsettingTypeRepository,
    ) {
    }

    /**
     * @param Gridcol[] $cols
     * @return array
     */
    public function getSettingsCols(Gridscope $scope, array $cols): array
    {
        $colsWithSettings = [];
        $settings = $this->gridsettingRepository->allByScope($scope);
        $typesByName = $this->gridsettingTypeRepository->allByName();

        $settingsByColId = [];
        foreach ($settings as $setting) {
            $key = $setting->getSettingKey();
            $settingType = $typesByName[$key] ?? null;
            $colId = $setting->getGridcol()->getId();
            $settingsByColId[$colId][] = ['setting' => $setting, 'type' => $settingType];
        }

        foreach ($cols as $col) {
            $colId = $col->getId();
            $setting = [
                'col_id' => $colId,
                'col_name' => $col->getName(),
                'attrs' => $settingsByColId[$colId] ?? null,
            ];
            $colsWithSettings[] = $setting;
        }

        return $colsWithSettings;
    }
}