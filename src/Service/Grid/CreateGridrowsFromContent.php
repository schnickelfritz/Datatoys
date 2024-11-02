<?php

namespace App\Service\Grid;

use App\Entity\Gridrow;
use App\Entity\Gridtable;
use App\Repository\GridrowRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CreateGridrowsFromContent
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GridrowRepository $rowRepository,
    )
    {
    }

    /**
     * @param Gridtable $table
     * @param int $lineNumberMax
     * @param list<string> $options
     * @return array<int, Gridrow>
     */
    public function gridrowsCreateOrUpdate(Gridtable $table, int $lineNumberMax, array $options): array
    {
        $existingRows = [];
        if (in_array('UPDATE', $options)) {
            $existingRows = $this->existingRowsByLinenumber($table);
        }
        $rows = [];
        for ($lineNumber = 1; $lineNumber <= $lineNumberMax; $lineNumber++) {
            if (isset($existingRows[$lineNumber])) {
                $rows[$lineNumber] = $existingRows[$lineNumber];
                continue;
            }
            $row = new Gridrow();
            $row
                ->setGridtable($table)
                ->setLineNumber($lineNumber)
            ;
            $this->entityManager->persist($row);
            $rows[$lineNumber] = $row;
        }
        $this->entityManager->flush();

        return $rows;
    }

    /**
     * @param Gridtable $table
     * @return array<int, Gridrow>
     */
    private function existingRowsByLinenumber(Gridtable $table): array
    {
        $existingRows = $this->rowRepository->allByTable($table);
        $existingRowsByLinenumber = [];
        foreach ($existingRows as $existingRow) {
            $existingRowsByLinenumber[$existingRow->getLineNumber()] = $existingRow;
        }

        return $existingRowsByLinenumber;
    }
}