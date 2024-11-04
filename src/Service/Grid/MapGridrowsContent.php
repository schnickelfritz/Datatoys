<?php

namespace App\Service\Grid;

use App\Entity\Gridtable;
use App\Repository\GridcolRepository;
use App\Repository\GridrowRepository;
use App\Struct\GridContentRow;

final readonly class MapGridrowsContent
{
    public function __construct(
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
        $rows = $this->rowRepository->allByTable($table);
        $colNames = $this->colRepository->allNames();
        foreach ($rows as $row) {
            $cells = $row->getGridcells();
            $values = [];
            foreach ($cells as $cell) {
                $colId = $cell->getGridcol()->getId();
                $colName = $colNames[$colId];
                $cellValue = $cell->getValue();
                $values[$colName] = $cellValue;
            }
            $mapItems[] = new GridContentRow($row, $values);
        }

        return $mapItems;
    }
}