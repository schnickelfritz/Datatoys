<?php

namespace App\Service\Grid;

use App\Entity\Gridcol;
use App\Entity\Gridscope;
use App\Enum\UserSettingEnum;
use App\Repository\GridcolRepository;
use App\Repository\GridscopeColRepository;
use App\Service\UserSetting\GetUserSetting;
use App\Trait\StringExplodeTrait;

final readonly class GetFilteredGridcols
{
    use StringExplodeTrait;

    public function __construct(
        private GridcolRepository $gridcolRepository,
        private GridscopeColRepository $gridscopeColRepository,
        private GetUserSetting $getUserSetting,
    )
    {
    }

    /**
     * @return array<int, array<int, Gridcol>|string>
     */
    public function getColsAndFilter(?Gridscope $scope = null): array
    {
        $allColumns = $this->gridcolRepository->findAll();

        $filter = $this->getUserSetting->getSetting(UserSettingEnum::GRIDCOL_FILTER);
        $filterSanitized = strtolower(trim($filter));

        if ($filterSanitized === '') {
            $columnsByScope = $this->columnsByScope($scope);
            $returnColumns = $columnsByScope === null ? $this->gridcolRepository->allColumnsFiltered() : $columnsByScope;
            return [$returnColumns, $filter];
        }

        $names = $this->explode($filter, ['|', ';', "\r", "\n", "\t"]);
        $namesSanitized = array_map(fn($item) => strtolower(trim($item)), $names);

        $columns = [];
        foreach ($allColumns as $column) {
            $name = $column->getName();
            if ($this->isFilterMatch($name, $filterSanitized, $namesSanitized)) {
                $columns[] = $column;
            }
        }

        return [$columns, $filter];
    }

    /**
     * @param string[] $names
     */
    private function isFilterMatch(string $name, string $filter, array $names): bool
    {
        if (count($names) < 2) {
            if (str_starts_with($filter,'::')) {
                return true;
            }
            return (str_contains($name, $filter));
        }

        return in_array($name, $names);
    }

    /**
     * @return Gridscope[]|null
     */
    private function columnsByScope(?Gridscope $scope): ?array
    {
        if ($scope === null) {
            return null;
        }
        $colsInScope = $this->gridscopeColRepository->allColsInScope($scope);
        return count($colsInScope) < 1 ? null : $colsInScope;
    }
}