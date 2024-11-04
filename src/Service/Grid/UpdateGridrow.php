<?php

namespace App\Service\Grid;

use App\Entity\Gridrow;
use App\Repository\GridcellRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UpdateGridrow
{

    public function __construct(
        private GridcellRepository $cellRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * @param array<int,string|null> $cellValues
     */
    public function updateCells(Gridrow $row, array $cellValues): int
    {
        $numberOfChanges = 0;
        $cellIds = array_keys($cellValues);
        $cells = $this->cellRepository->findBy(['id'=>$cellIds, 'gridrow'=>$row]);
        foreach ($cells as $cell) {
            $cellId = $cell->getId();
            if (!in_array($cellId, $cellIds)) {
                continue;
            }
            $newValue = $cellValues[$cellId];
            if ($newValue !== $cell->getValue()) {
                $cell->setValue($newValue);
                $this->entityManager->persist($cell);
                $numberOfChanges++;
            }
        }
        $this->entityManager->flush();

        return $numberOfChanges;
    }
}