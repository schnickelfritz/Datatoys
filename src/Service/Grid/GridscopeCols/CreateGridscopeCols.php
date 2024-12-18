<?php

declare(strict_types=1);

namespace App\Service\Grid\GridscopeCols;

use App\Entity\Gridcol;
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

    /**
     * @param string $multipleNames
     * @param array<int, Gridscope> $scopes
     * @return void
     */
    public function createMultiple(string $multipleNames, array $scopes): void
    {
        $names = $this->explode($multipleNames, ['|', ';', "\r", "\n", "\t"]);
        $cols = $this->colRepository->findBy(['name' => $names]);
        foreach ($scopes as $scope) {
            if (!$scope instanceof Gridscope) {
                continue;
            }
            $this->createForScope($cols, $scope);
        }
        $this->entityManager->flush();
    }

    /**
     * @param array<int, Gridcol> $cols
     * @param Gridscope $scope
     * @return void
     */
    private function createForScope(array $cols, Gridscope $scope): void
    {
        $existingCols = $this->gridscopeColRepository->allColsInScope($scope);
        foreach ($cols as $col) {
            if (in_array($col, $existingCols)) {
                continue;
            }
            $newScopecol = new GridscopeCol();
            $newScopecol->setScope($scope)->setCol($col);
            $this->entityManager->persist($newScopecol);
        }
    }

}
