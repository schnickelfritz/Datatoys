<?php

namespace App\Service\Grid\GridFinder;

use App\Entity\Gridcol;
use App\Entity\Gridrow;
use App\Entity\Gridtable;
use App\Repository\GridcellRepository;
use App\Repository\GridrowRepository;

final readonly class FindGridrow
{

    public function __construct(
        private GridrowRepository $gridrowRepository,
    )
    {
    }

    public function rowInCol(Gridtable $table, Gridcol $col, string $value): ?Gridrow
    {
        $colId = $col->getId();
        $rowsInTable = $this->gridrowRepository->allByTable($table);

        foreach ($rowsInTable as $row) {
            foreach ($row->getGridcells() as $cell) {
                $rowId = $cell->getGridrow()->getId();
                $cellColId = $cell->getGridcol()->getId();
                $cellValue = $cell->getValue();
                if ($cellColId === $colId && $cellValue === $value) {
                    return $row;
                }
            }
        }

        return null;
    }
}