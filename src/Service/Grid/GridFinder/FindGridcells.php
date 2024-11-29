<?php

namespace App\Service\Grid\GridFinder;

use App\Repository\GridcellRepository;
use App\Trait\StringExplodeTrait;

final readonly class FindGridcells
{
    use StringExplodeTrait;

    public function __construct(
        private GridcellRepository $gridcellRepository,
    )
    {
    }

    public function findCells(string $filter): array
    {
        $filterSanitized = strtolower(trim($filter));
        if ($filterSanitized === '') {
            return [];
        }

        $multipleSearchTermas = $this->explode($filterSanitized, ['|', ';', "\r", "\n", "\t"]);
        if (count($multipleSearchTermas) < 2) {
            return $this->gridcellRepository->allCellsFiltered($filterSanitized);
        }

        return $this->gridcellRepository->findCellsByMultipleTerms($multipleSearchTermas);

    }
}