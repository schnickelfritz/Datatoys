<?php

namespace App\Service\Grid\GridValidate;

use App\Entity\Gridtable;
use App\Repository\GridrowRepository;
use App\Repository\GridscopeColRepository;
use App\Service\Grid\Gridsettings\GetSettingsCols;

final readonly class ValidateGridtable
{

    public function __construct(
        private GetSettingsCols $getSettingsCols,
        private GridrowRepository $gridrowRepository,
        private GridscopeColRepository $gridscopeColRepository,
    )
    {
    }

    public function validateTable(Gridtable $table): string
    {
        $rows = $this->gridrowRepository->allByTable($table);
        $scope = $table->getScope();
        $columns = $this->gridscopeColRepository->allColsInScope($scope);
        $settings = $this->getSettingsCols->getSettingsCols($scope, $columns);
        dd($settings);
    }
}