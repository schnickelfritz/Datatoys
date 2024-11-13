<?php

namespace App\Service\Grid\Gridcontent;

use App\Entity\Gridcell;
use App\Entity\Gridrow;
use App\Entity\Gridtable;
use App\Repository\GridcolRepository;
use App\Repository\GridrowRepository;
use App\Service\Grid\Gridsettings\GetSortindexedCols;
use App\Struct\GridContentRow;

final readonly class MapGridrowsContent
{
    public function __construct(
        private GetSortindexedCols $sortindexedCols,
        private GridrowRepository $rowRepository,
        private GridcolRepository $colRepository,
    )
    {
    }

    /**
     * @param Gridtable $table
     * @return array<int, GridContentRow>
     */
    public function mapRows(Gridtable $table): array
    {
        $mapItems = [];
        $sortindexedCols = $this->sortindexedCols->sortIndexed($table->getScope());
        $rows = $this->rowRepository->allByTable($table);
        $colNames = $this->colRepository->allNames();
        foreach ($rows as $row) {
            $values = $this->getColnameValuePairsSorted($colNames, $sortindexedCols, $row);
            $mapItems[] = new GridContentRow($row, $values);
        }

        return $mapItems;
    }

    /**
     * @param array<int, string> $colNames
     * @param array<int, int> $sortindexedCols
     * @return array<string, ?string>
     */
    private function getColnameValuePairsSorted(array $colNames, array $sortindexedCols, Gridrow $row): array
    {
        $sortindices = [];
        $valuesByColName = [];
        
        $cells = $row->getGridcells();
        foreach ($cells as $cell) {
            $colId = $cell->getGridcol()->getId();
            $colName = $colNames[$colId];
            $sortindices[$colName] = $sortindexedCols[$colId] ?? 999999;
            $cellValue = $cell->getValue();
            $valuesByColName[$colName] = $cellValue;
        }

        asort($sortindices, SORT_NUMERIC);
        $values = [];
        foreach (array_keys($sortindices) as $colName) {
            $values[$colName] = $valuesByColName[$colName];
        }

        return $values;
    }
}