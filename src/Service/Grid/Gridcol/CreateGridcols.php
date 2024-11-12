<?php

declare(strict_types=1);

namespace App\Service\Grid\Gridcol;

use App\Entity\Gridcol;
use App\Repository\GridcolRepository;
use App\Trait\StringExplodeTrait;
use Doctrine\ORM\EntityManagerInterface;
use function in_array;

final readonly class CreateGridcols
{
    use StringExplodeTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GridcolRepository $colRepository,
    ) {
    }

    /**
     * @return array<int, Gridcol>
     */
    public function createMultiple(string $multipleNames): array
    {
        $existingNames = $this->colRepository->allNames();
        $newCols = [];

        $names = $this->explode($multipleNames, ['|', ';', "\r", "\n", "\t"]);
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
