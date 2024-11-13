<?php

declare(strict_types=1);

namespace App\Service\Grid\Gridcontent;

use App\Entity\Gridcell;
use App\Entity\Gridcol;
use App\Entity\Gridrow;
use App\Repository\GridcellRepository;
use Doctrine\ORM\EntityManagerInterface;
use function in_array;

final readonly class CreateGridcellsFromContent
{

    // TODO 2024-11-04 ME: same-name-options (skip, concat) berücksichtigen

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GridcellRepository $cellRepository,
    ) {
    }

    /**
     * @param array<int, array<int, string>> $matrix
     * @param array<int, Gridrow>            $linenumberedRows
     * @param array<int, Gridcol>            $cols
     * @param array<int, string>             $options
     */
    public function gridcellsCreateOrUpdate(array $matrix, array $linenumberedRows, array $cols, array $options): bool
    {
        $existingCells = [];
        if (in_array('UPDATE', $options)) {
            $existingCells = $this->existingCellsByRowAndColIds($linenumberedRows);
        }

        for ($lineNumber = 1; $lineNumber <= max(array_keys($matrix)); ++$lineNumber) {
            $matrixRow = $matrix[$lineNumber];
            for ($colNumber = 0; $colNumber <= max(array_keys($matrixRow)); ++$colNumber) {
                $isCreateSuccess = $this->gridCellCreateOrUpdate($lineNumber, $colNumber, $matrixRow, $linenumberedRows, $cols, $existingCells);
                if (!$isCreateSuccess) {
                    return false;
                }
            }
        }
        $this->entityManager->flush();
        return true;
    }

    /**
     * @param array<int, string>               $matrixRow
     * @param array<int, Gridrow>              $rows
     * @param array<int, Gridcol>              $cols
     * @param array<int, array<int, Gridcell>> $existingCells
     */
    private function gridCellCreateOrUpdate(
        int $lineNumber,
        int $colNumber,
        array $matrixRow,
        array $rows,
        array $cols,
        array $existingCells
    ): bool {
        $value = $matrixRow[$colNumber];
        if (!isset($rows[$lineNumber])) {
            return false;
        }
        $row = $rows[$lineNumber];
        $rowId = $row->getId();
        $col = $cols[$colNumber];
        $colId = $col->getId();
        if (isset($existingCells[$rowId][$colId])) {
            $existingCells[$rowId][$colId]->setValue($value);

            return true;
        }
        $cell = new Gridcell();
        $cell
            ->setGridrow($row)
            ->setGridcol($col)
            ->setValue($value)
        ;
        $this->entityManager->persist($cell);

        return true;
    }

    /**
     * @param array<int, Gridrow> $rows
     *
     * @return array<int, array<int, Gridcell>>
     */
    private function existingCellsByRowAndColIds(array $rows): array
    {
        $existingCells = $this->cellRepository->allByRows($rows);
        $existigCellsByRowAndColIds = [];
        foreach ($existingCells as $existingCell) {
            $row = $existingCell->getGridrow();
            $col = $existingCell->getGridcol();
            $rowId = $row->getId();
            $colId = $col->getId();
            if ($rowId !== null && $colId !== null) {
                $existigCellsByRowAndColIds[$rowId][$colId] = $existingCell;
            }
        }

        return $existigCellsByRowAndColIds;
    }
}
