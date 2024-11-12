<?php

namespace App\Service\Geoguessr;

use App\Entity\Gridcell;
use App\Entity\Gridcol;
use App\Entity\Gridrow;
use App\Entity\Gridscope;
use App\Entity\Gridtable;
use App\Enum\UserSettingEnum;
use App\Repository\GridcellRepository;
use App\Repository\GridcolRepository;
use App\Repository\GridrowRepository;
use App\Repository\GridscopeColRepository;
use App\Repository\GridscopeRepository;
use App\Repository\GridtableRepository;
use App\Trait\StringExplodeTrait;

final readonly class GetGeoguessrData
{
    use StringExplodeTrait;

    private const GEOGUESSR_SCOPEKEY = 'geoguessr';
    private const GEOGUESSR_COLNAME_COUNTRY = 'land';
    private const GEOGUESSR_COLNAME_AREA = 'weltgebiet';
    private const GEOGUESSR_COLNAME_WILDCARD = 'wildcard';

    public function __construct(
        private GridscopeRepository $gridscopeRepository,
        private GridscopeColRepository $gridscopeColRepository,
        private GridtableRepository $gridtableRepository,
        private GridrowRepository $gridrowRepository,
        private GridcolRepository $gridcolRepository,
        private GridcellRepository $gridcellRepository,
    )
    {
    }

    /**
     * @return array<string, array<int|string, array<int, string>>>
     */
    public function getHits(): array
    {
        $table = $this->getTable();
        if (!$table instanceof Gridtable) {
            return [];
        }
        $hits = [];
        $activeRow = $this->getEmptyCountryRow();
        $setValues = $this->getSetValues();
        $cols = $this->gridcolRepository->findBy(['id' => array_keys($setValues)]);
        $cells = $this->gridcellRepository->allByCols($table, $cols);
        $countries = $this->gridcellRepository->allByColByRowId($table, $this->getCountryCol());

        foreach ($cells as $cell) {
            $colId = $cell->getGridcol()->getId();
            $row = $cell->getGridrow();
            $country = $countries[$row->getId()];
            if ($row === $activeRow) {
                continue;
            }
            if (!isset($setValues[$colId])) {
                continue;
            }
            $rowValues = $this->explode($cell->getValue());
            foreach ($setValues[$colId] as $setValue) {
                foreach ($rowValues as $rowValue) {
                    if ($rowValue === $setValue) {
                        $hits[$country][$colId][] = $setValue;
                    }
                }
            }
        }
        return $hits;
    }

    public function getTable(): ?Gridtable
    {
        $scope = $this->gridscopeRepository->findOneBy(['scopeKey' => self::GEOGUESSR_SCOPEKEY]);
        if (!$scope instanceof Gridscope) {
            return null;
        }

        return $this->gridtableRepository->findOneBy(['scope' => $scope]);
    }

    /**
     * @return array<int|string, array<string>>
     */
    public function getSetValues(): array
    {
        $row = $this->getEmptyCountryRow();
        if (!$row instanceof Gridrow) {
            return [];
        }
        $cells = $this->gridcellRepository->allByRow($row);
        $values = [];
        foreach ($cells as $cell) {
            $value = $cell->getValue();
            $col = $cell->getGridcol();
            $colname = $col->getName();
            $colId = $col->getId();
            if (!$this->isValidColValue($colname, $value)) {
                continue;
            }
            $values[$colId] = $this->explode($value);
        }
        return $values;
    }

    private function isValidColValue(string $colname, ?string $value): bool
    {
        if ($colname === self::GEOGUESSR_COLNAME_WILDCARD) {
            return false;
        }
        if ($colname === self::GEOGUESSR_COLNAME_COUNTRY) {
            return true;
        }
        if ($colname === self::GEOGUESSR_COLNAME_AREA) {
            return true;
        }
        if ($value !== '') {
            return true;
        }
        return false;
    }

    /**
     * @return array<int, string[]>
     */
    public function getAllValuesByCols(): array
    {
        $table = $this->getTable();
        if (!$table instanceof Gridtable) {
            return [];
        }

        return $this->gridcellRepository->valuesByColId($table);
    }

    /**
     * @return array<int, Gridcol>
     */
    public function allCols(): array
    {
        $scope = $this->gridscopeRepository->findOneBy(['scopeKey' => self::GEOGUESSR_SCOPEKEY]);
        if ($scope === null) {
            return [];
        }
        return $this->gridscopeColRepository->allColsInScope($scope);
    }

    /**
     * @return array<int, Gridcol>
     */
    public function allColsById(): array
    {
        $cols = [];
        foreach ($this->allCols() as $col) {
            $id = (int) $col->getId();
            $cols[$id] = $col;
        }

        return $cols;
    }

    /**
     * @return array<string, Gridcol>
     */
    public function allColsByName(): array
    {
        $cols = [];
        foreach ($this->allCols() as $col) {
            $cols[$col->getName()] = $col;
        }

        return $cols;
    }

    /**
     * @return array<int, string>
     */
    public function getAllColnames(): array
    {
        $colnames = [];
        foreach ($this->allCols() as $col) {
            $id = (int) $col->getId();
            $colnames[$id] = $col->getName();
        }

        return $colnames;
    }

    public function getWildcard(): ?string
    {
        $row = $this->getEmptyCountryRow();
        if ($row === null) {
            return null;
        }
        $cell = $this->getCell($row, $this->getWildcardCol());

        return $cell?->getValue();
    }

    public function getCountryCol(): ?Gridcol
    {
        return $this->gridcolRepository->findOneBy(['name' => self::GEOGUESSR_COLNAME_COUNTRY]);
    }

    public function getAreaCol(): ?Gridcol
    {
        return $this->gridcolRepository->findOneBy(['name' => self::GEOGUESSR_COLNAME_AREA]);
    }

    public function getWildcardCol(): ?Gridcol
    {
        return $this->gridcolRepository->findOneBy(['name' => self::GEOGUESSR_COLNAME_WILDCARD]);
    }

    public function getEmptyCountryRow(): ?Gridrow
    {
        $table = $this->getTable();
        $countryCol = $this->getCountryCol();
        if (!$countryCol instanceof Gridcol || !$table instanceof Gridtable) {
            return null;
        }
        $cells = $this->gridcellRepository->findValue($table, $countryCol, '');
        if (!empty($cells)) {
            return $cells[0]->getGridrow();
        }

        return null;
    }

    public function maxLinenumber(): int
    {
        $table = $this->getTable();
        if (!$table instanceof Gridtable) {
            return 0;
        }
        return $this->gridrowRepository->maxLinenumber($table);
    }

    public function getCell(Gridrow $row, Gridcol $col): ?Gridcell
    {
        return $this->gridcellRepository->findOneBy(['gridrow' => $row, 'gridcol' => $col]);
    }
}