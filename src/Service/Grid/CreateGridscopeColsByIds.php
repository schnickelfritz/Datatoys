<?php

declare(strict_types=1);

namespace App\Service\Grid;

use App\Entity\Gridcol;
use App\Entity\Gridscope;
use App\Entity\GridscopeCol;
use App\Repository\GridcolRepository;
use App\Repository\GridscopeColRepository;
use App\Repository\GridscopeRepository;
use App\Trait\StringExplodeTrait;
use Doctrine\ORM\EntityManagerInterface;

use function in_array;

final readonly class CreateGridscopeColsByIds
{
    use StringExplodeTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GridscopeRepository $scopeRepository,
        private GridscopeColRepository $gridscopeColRepository,
        private GridcolRepository $colRepository,
    ) {
    }

    /**
     * @param int[] $colIds
     * @param int[] $scopeIds
     */
    public function createMultipleByIds(array $colIds, array $scopeIds): void
    {
        $cols = $this->colRepository->findBy(['id' => $colIds]);
        $scopes = $this->scopeRepository->findBy(['id' => $scopeIds]);
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
