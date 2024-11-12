<?php

namespace App\Service\Grid\Gridrow;

use App\Entity\Gridcell;
use App\Entity\Gridrow;
use App\Entity\Gridtable;
use App\Repository\GridcolRepository;
use App\Repository\GridrowRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CreateGridrow
{

    public function __construct(
        private GridrowRepository $gridrowRepository,
        private GridcolRepository $gridcolRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * @param array<int, string> $values
     */
    public function createByValues(Gridtable $table, array $values): ?Gridrow
    {
        $maxLinenumber = $this->gridrowRepository->maxLinenumber($table);
        $row = new Gridrow();
        $row->setGridtable($table)->setLineNumber($maxLinenumber+1);
        $this->entityManager->persist($row);

        $colIds = array_keys($values);
        $cols = $this->gridcolRepository->findBy(['id' => $colIds]);
        foreach ($cols as $col) {
            $cell = new Gridcell();
            $value = $values[$col->getId()];
            $cell
                ->setGridrow($row)
                ->setGridcol($col)
                ->setValue($value);
            $this->entityManager->persist($cell);
        }

        $this->entityManager->flush();

        return $row;
    }

}