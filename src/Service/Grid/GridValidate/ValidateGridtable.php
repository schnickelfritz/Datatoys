<?php

namespace App\Service\Grid\GridValidate;

use App\Entity\Gridtable;
use App\Repository\GridrowRepository;
use App\Repository\GridsettingRepository;

final readonly class ValidateGridtable
{

    public function __construct(
        private GridsettingRepository $gridsettingRepository,
        private GridrowRepository     $gridrowRepository,
        private ValidateGridcell      $valCell,
        private ValidateFileOfCell    $valFile,
    )
    {
    }

    public function validateTable(Gridtable $table): array
    {
        $rows = $this->gridrowRepository->allByTable($table);
        $scope = $table->getScope();
        $settings = $this->gridsettingRepository->allByScope($scope);

        $settingsMapped = [];
        foreach ($settings as $setting) {
            $settingColId = $setting->getGridcol()->getId();
            $settingsMapped[$settingColId][] = $setting;
        }

        $fails = [];
        foreach ($rows as $row) {
            foreach ($row->getGridcells() as $cell) {
                $rowId = $cell->getGridrow()->getId();
                $colId = $cell->getGridcol()->getId();
                if (!isset($settingsMapped[$colId])) {
                    continue;
                }
                $fails = $this->failsMerge($fails, $rowId, $colId, $this->valCell->val($cell, $settingsMapped[$colId]));
                $fails = $this->failsMerge($fails, $rowId, $colId, $this->valFile->val($cell, $settingsMapped[$colId]));
            }
        }

        return $fails;
    }

    private function failsMerge(array $allFails, int $rowId, int $colId, array $newFails): array
    {
        if (empty($newFails)) {
            return $allFails;
        }
        if (!isset($allFails[$rowId][$colId])) {
            $allFails[$rowId][$colId] = $newFails;

            return $allFails;
        }
        $allFails[$rowId][$colId] = array_merge($allFails[$rowId][$colId], $newFails);
        return $allFails;
    }
}