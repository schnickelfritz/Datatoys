<?php

namespace App\Service\Grid;

use App\Entity\Gridcol;
use App\Repository\GridcolRepository;
use App\Trait\StringExplodeTrait;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CreateGridcols
{

    use StringExplodeTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GridcolRepository $colRepository,
    )
    {
    }

    public function createMultiple(string $multipleNames): array
    {
        $existingNames = $this->colRepository->allNames();
        $newCols = [];

        $names = $this->explode($multipleNames, [',', ';', "\r", "\n", "\t"]);
        foreach ($names as $name) {
            $nameSanitized = trim(strtolower($name));
            if (in_array($nameSanitized, $existingNames)) {
                continue;
            }
            $newCols[] = $this->create($nameSanitized);
            $existingNames[] = $nameSanitized;
        }

        $this->entityManager->flush();

        return $newCols;
    }

    private function create(string $name): Gridcol
    {
        $col = new Gridcol();
        $col->setName($name);
        $this->entityManager->persist($col);

        return $col;
    }
}