<?php

declare(strict_types=1);

namespace App\Service\Grid;

use App\Entity\Gridscope;
use App\Entity\GridscopeCol;
use App\Repository\GridcolRepository;
use App\Repository\GridscopeColRepository;
use App\Trait\StringExplodeTrait;
use Doctrine\ORM\EntityManagerInterface;

use function in_array;

final readonly class CreateGridscopeCols
{
    use StringExplodeTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GridscopeColRepository $gridscopeColRepository,
        private GridcolRepository $colRepository,
    ) {
    }

    public function createMultiple(string $multipleNames, Gridscope $scope): void
    {
        $names = $this->explode($multipleNames, [',', ';', "\r", "\n", "\t"]);
        $cols = $this->colRepository->findBy(['name' => $names]);
        $existingCols = $this->gridscopeColRepository->allColsInScope($scope);
        foreach ($cols as $col) {
            if (in_array($col, $existingCols)) {
                continue;
            }
            $newScopecol = new GridscopeCol();
            $newScopecol->setScope($scope)->setCol($col);
            $this->entityManager->persist($newScopecol);
        }
        $this->entityManager->flush();
    }
}
