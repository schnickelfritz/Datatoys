<?php

namespace App\Service\Grid\Gridfile;

use App\Entity\Gridtable;
use App\Repository\GridrowRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class RemoveFilelinksFromCells
{

    public function __construct(
        private GridrowRepository     $gridrowRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function removeFilelinksFromCells(Gridtable $table): void
    {
        $rows = $this->gridrowRepository->allByTable($table);
        foreach ($rows as $row) {
            foreach ($row->getGridcells() as $cell) {
                $cell->setGridfile(null);
            }
        }
        $this->entityManager->flush();
    }
}