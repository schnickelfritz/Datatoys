<?php

namespace App\Service\Grid\Gridcontent;

use App\Entity\Gridcol;
use App\Entity\Gridrow;
use App\Entity\Gridtable;
use App\Repository\GridcolRepository;
use App\Service\Grid\GridFinder\FindGridrow;

final readonly class CreateGridrowsByCellValueFromContent
{

    public function __construct(
        private FindGridrow       $findGridrow,
        private GridcolRepository $gridcolRepository,
    )
    {
    }

    public function gridrowsCreateOrUpdate(Gridtable $table, string $updateKey, array $matrix): array
    {
        $col = $this->gridcolRepository->findOneBy(['name' => $updateKey]);
        if (!$col instanceof Gridcol) {
            return [];
        }

        $columnNames = $matrix[0];
        $colUpdateNumber = array_search($updateKey, $columnNames);
        if ($colUpdateNumber === false) {
            return [];
        }

        $rows = [];
        for ($lineNumber = 1; $lineNumber <= max(array_keys($matrix)); ++$lineNumber) {
            $findValue = $matrix[$lineNumber][$colUpdateNumber];
            $row = $this->findGridrow->rowInCol($table, $col, $findValue);
            if ($row instanceof Gridrow) {
                $linNumberFound = $row->getLineNumber();
                $rows[$linNumberFound] = $row;
            }
        }

        return $rows;
    }
}