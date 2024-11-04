<?php

namespace App\Service\Grid;

use App\Repository\GridcolRepository;
use App\Repository\GridscopeColRepository;
use App\Trait\StringExplodeTrait;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DeleteGridscopeCols
{
    use StringExplodeTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GridscopeColRepository $gridscopeColRepository,
        private GridcolRepository $colRepository,
    )
    {
    }

    public function deleteMultiple(string $multipleNames): void
    {
        $names = $this->explode($multipleNames, [',', ';', "\r", "\n", "\t"]);
        $cols = $this->colRepository->findBy(['name' => $names]);
        $scopeCols = $this->gridscopeColRepository->findBy(['col'=>$cols]);
        foreach ($scopeCols as $scopeCol) {
            $this->entityManager->remove($scopeCol);
        }
        $this->entityManager->flush();
    }
}