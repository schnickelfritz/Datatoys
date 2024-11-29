<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Gridcell;
use App\Entity\Gridcol;
use App\Entity\Gridrow;
use App\Entity\Gridtable;
use App\Trait\StringExplodeTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use function is_array;

/**
 * @extends ServiceEntityRepository<Gridcell>
 */
class GridcellRepository extends ServiceEntityRepository
{
    use StringExplodeTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gridcell::class);
    }

    /**
     * @param array<int, Gridrow> $rows
     *
     * @return array<int, Gridcell>
     */
    public function allByRows(array $rows): array
    {
        $cells = $this->createQueryBuilder('c')
            ->andWhere('c.gridrow IN (:rows)')
            ->setParameter('rows', $rows)
            ->getQuery()
            ->getResult()
        ;

        return is_array($cells) ? $cells : [];
    }

    /**
     * @return array<int, Gridcell>
     */
    public function allByRow(Gridrow $row): array
    {
        $cells = $this->createQueryBuilder('c')
            ->andWhere('c.gridrow =:row')
            ->setParameter('row', $row)
            ->getQuery()
            ->getResult()
        ;

        return is_array($cells) ? $cells : [];
    }

    /**
     * @return Gridcell[]
     */
    public function findValue(Gridtable $table, Gridcol $col, ?string $value): array
    {
        $cells = $this->createQueryBuilder('c')
            ->leftJoin('c.gridrow', 'rows')
            ->addSelect('rows')
            ->andWhere('rows.gridtable = :table')
            ->setParameter('table', $table)
            ->andWhere('c.gridcol = :col')
            ->setParameter('col', $col)
            ->andWhere('c.value = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult()
        ;

        return is_array($cells) ? $cells : [];
    }

    /**
     * @return Gridcell[]
     */
    public function allInTable(Gridtable $table): array
    {
        $cells = $this->createQueryBuilder('c')
            ->leftJoin('c.gridrow', 'rows')
            ->addSelect('rows')
            ->andWhere('rows.gridtable = :table')
            ->setParameter('table', $table)
            ->getQuery()
            ->getResult()
        ;
        if (!is_array($cells)) {
            return [];
        }

        return $cells;
    }

    /**
     * @return array<int, array<int, string|null>>
     */
    public function valuesByRowColId(Gridtable $table): array
    {
        $cells = $this->allInTable($table);
        $byRowId = [];
        foreach ($cells as $cell) {
            $rowId = $cell->getGridrow()->getId();
            $colId = $cell->getGridcol()->getId();
            if ($rowId !== null && $colId !== null) {
                $byRowId[$rowId][$colId] = $cell->getValue();
            }
        }

        return $byRowId;
    }

    /**
     * @return array<int, string[]>
     */
    public function valuesByColId(Gridtable $table): array
    {
        $cells = $this->allInTable($table);
        $byColId = [];
        foreach ($cells as $cell) {
            $values = array_unique($this->explode($cell->getValue()));
            if (empty($values)) {
                continue;
            }
            $colId = (int) $cell->getGridcol()->getId();
            if (!isset($byColId[$colId])) {
                $byColId[$colId] = $values;
                continue;
            }
            foreach ($values as $value) {
                if (!in_array($value, $byColId[$colId])) {
                    $byColId[$colId][] = $value;
                }
            }
        }

        return $byColId;
    }

    /**
     * @param Gridcol[] $cols
     * @return Gridcell[]
     */
    public function allByCols(Gridtable $table, array $cols): array
    {
        $cells = $this->createQueryBuilder('c')
            ->leftJoin('c.gridrow', 'rows')
            ->addSelect('rows')
            ->andWhere('rows.gridtable = :table')
            ->setParameter('table', $table)
            ->andWhere('c.gridcol in (:cols)')
            ->setParameter('cols', $cols)
            ->getQuery()
            ->getResult()
        ;
        if (!is_array($cells)) {
            return [];
        }

        return $cells;

    }

    /**
     * @return array<int, string>
     */
    public function allByColByRowId(Gridtable $table, ?Gridcol $col): array
    {
        if ($col === null) {
            return [];
        }

        $cells = $this->createQueryBuilder('c')
            ->leftJoin('c.gridrow', 'rows')
            ->addSelect('rows')
            ->andWhere('rows.gridtable = :table')
            ->setParameter('table', $table)
            ->andWhere('c.gridcol = :col')
            ->setParameter('col', $col)
            ->getQuery()
            ->getResult()
        ;
        if (!is_array($cells)) {
            return [];
        }

        $byColId = [];
        foreach ($cells as $cell) {
            $byColId[$cell->getGridrow()->getId()] = $cell->getValue();
        }
        return $byColId;

    }

    /**
     * @return Gridcell[]
     */
    public function allCellsFiltered(string $filter): array
    {
        $cells = $this->createQueryBuilder('c')
            ->andWhere('c.value LIKE :filter')
            ->setParameter('filter', '%' . $filter . '%')
            ->leftJoin('c.gridrow', 'rows')
            ->addSelect('rows')
            ->leftJoin('c.gridcol', 'cols')
            ->addSelect('cols')
            ->getQuery()
            ->getResult()
        ;

        return is_array($cells) ? $cells : [];
    }

    public function findCellsByMultipleTerms(array $terms): array
    {
        $tables = $this->createQueryBuilder('c')
            ->andWhere('c.value IN (:terms)')
            ->setParameter('terms', $terms)
            ->leftJoin('c.gridrow', 'rows')
            ->addSelect('rows')
            ->leftJoin('c.gridcol', 'cols')
            ->addSelect('cols')
            ->getQuery()
            ->getResult()
        ;
        return is_array($tables) ? $tables : [];
    }

}
