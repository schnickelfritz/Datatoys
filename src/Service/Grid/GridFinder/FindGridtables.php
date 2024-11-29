<?php

namespace App\Service\Grid\GridFinder;

use App\Repository\GridtableRepository;
use App\Trait\StringExplodeTrait;

final readonly class FindGridtables
{
    use StringExplodeTrait;

    public function __construct(
        private GridtableRepository $gridtableRepository,
    )
    {
    }

    public function findTables(string $filter): array
    {
        $filterSanitized = strtolower(trim($filter));
        if ($filterSanitized === '') {
            return [];
        }

        $multipleSearchTerms = $this->explode($filterSanitized, ['|', ';', "\r", "\n", "\t"]);
        if (count($multipleSearchTerms) < 2) {
            return $this->gridtableRepository->allTablesFiltered($filterSanitized);
        }

        return $this->gridtableRepository->findTablesByMultipleTerms($multipleSearchTerms);
    }
}