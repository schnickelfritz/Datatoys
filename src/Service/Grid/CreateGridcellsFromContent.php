<?php

namespace App\Service\Grid;

use App\Entity\Gridcell;
use App\Entity\Gridcol;
use App\Entity\Gridrow;
use App\Repository\GridcellRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CreateGridcellsFromContent
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GridcellRepository $cellRepository,
    )
    {
    }

    /**
     * @param array<int, array<string, string>> $matrix
     * @param array<int, Gridrow> $rows
     * @param array<int, Gridcol> $cols
     * @param array $options
     * @return void
     */
    public function gridcellsCreateOrUpdate(array $matrix, array $rows, array $cols, array $options): void
    {
        $existingCells = [];
        if (in_array('UPDATE', $options)) {
            $existingCells = $this->existingCellsByRowAndColIds($rows);
        }

        for ($lineNumber = 1; $lineNumber <= max(array_keys($matrix)); $lineNumber++) {
            $matrixRow = $matrix[$lineNumber];
            for ($colNumber = 0; $colNumber <= max(array_keys($matrixRow)); $colNumber++) {
                $this->gridCellCreateOrUpdate($lineNumber, $colNumber, $matrixRow, $rows, $cols, $existingCells);
            }
        }
        $this->entityManager->flush();
    }

    /**
     * @param int $lineNumber
     * @param int $colNumber
     * @param array<int, string> $matrixRow
     * @param array<int, Gridrow> $rows
     * @param array<int, Gridcol> $cols
     * @param array<int, array<int, Gridcell>> $existingCells
     * @return void
     */
    private function gridCellCreateOrUpdate(
        int $lineNumber,
        int $colNumber,
        array $matrixRow,
        array $rows,
        array $cols,
        array $existingCells
    ): void
    {
        $value = $matrixRow[$colNumber];
        $row = $rows[$lineNumber];
        $rowId = $row->getId();
        $col = $cols[$colNumber];
        $colId = $col->getId();
        if (isset($existingCells[$rowId][$colId])) {
            $existingCells[$rowId][$colId]->setValue($value);
            return;
        }
        $cell = new Gridcell();
        $cell
            ->setGridrow($row)
            ->setGridcol($col)
            ->setValue($value)
        ;
        $this->entityManager->persist($cell);
    }

    /**
     * @param array<int, Gridrow> $rows
     * @return array<int, array<int, Gridcell>
     */
    private function existingCellsByRowAndColIds(array $rows): array
    {
        $existingCells = $this->cellRepository->allByRows($rows);
        $existigCellsByRowAndColIds = [];
        foreach ($existingCells as $existingCell) {
            $row = $existingCell->getGridrow();
            $col = $existingCell->getGridcol();
            $existigCellsByRowAndColIds[$row->getId()][$col->getId()] = $existingCell;
        }

        return $existigCellsByRowAndColIds;
    }

}