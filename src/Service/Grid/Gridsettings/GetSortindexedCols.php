<?php

namespace App\Service\Grid\Gridsettings;

use App\Entity\Gridscope;
use App\Repository\GridsettingRepository;

final readonly class GetSortindexedCols
{

    public function __construct(
        private GridsettingRepository $gridsettingRepository,
    )
    {
    }

    /**
     * @return array<int, int>
     */
    public function sortIndexed(Gridscope $scope): array
    {
        $byColnames = [];
        $settings = $this->gridsettingRepository->allByScope($scope);
        foreach ($settings as $setting) {
            $settingsKey = $setting->getSettingKey();
            if ($settingsKey !== 'SORTINDEX') {
                continue;
            }
            $colId = $setting->getGridcol()->getId();
            $parameter = $setting->getParameter();
            if ($parameter !== null && $parameter !== '') {
                $byColnames[$colId] = (int) $setting->getParameter();
            }
        }

        return $byColnames;

    }
}