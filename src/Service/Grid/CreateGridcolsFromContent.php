<?php

declare(strict_types=1);

namespace App\Service\Grid;

use App\Entity\Gridcol;
use App\Repository\GridcolRepository;
use Doctrine\ORM\EntityManagerInterface;

use function in_array;

final readonly class CreateGridcolsFromContent
{
    public function __construct(
        private GridcolRepository $colRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param list<string> $columnNames
     * @param list<string> $options
     *
     * @return array<int, Gridcol>|null
     */
    public function getOrCreateGridcols(array $columnNames, array $options): ?array
    {
        $existingColsByName = $this->existingColsByName();
        $existingNames = array_keys($existingColsByName);
        $missingNames = array_unique(array_diff($columnNames, $existingNames));
        if (!empty($missingNames) && !in_array('ALLOW_NEW_COLUMNS', $options)) {
            return null;
        }

        $columns = [];
        foreach ($columnNames as $columnName) {
            if (isset($existingColsByName[$columnName])) {
                $columns[] = $existingColsByName[$columnName];
                continue;
            }
            $col = new Gridcol();
            $col->setName($columnName);
            $this->entityManager->persist($col);
            $columns[] = $col;
        }
        $this->entityManager->flush();

        return $columns;
    }

    /**
     * @return array<string, Gridcol>
     */
    private function existingColsByName(): array
    {
        $columns = [];
        foreach ($this->colRepository->findAll() as $col) {
            $columns[$col->getName()] = $col;
        }

        return $columns;
    }
}
