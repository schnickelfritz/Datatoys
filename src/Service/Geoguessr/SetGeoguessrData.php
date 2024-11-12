<?php

namespace App\Service\Geoguessr;

use App\Entity\Gridcell;
use App\Entity\Gridcol;
use App\Entity\Gridrow;
use App\Entity\Gridtable;
use App\Enum\UserSettingEnum;
use App\Repository\GridcellRepository;
use App\Trait\StringExplodeTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\UnicodeString;

final readonly class SetGeoguessrData
{

    use StringExplodeTrait;

    private const COL_SEPARATOR = '#';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GetGeoguessrData $getGeoguessrData,
        private GridcellRepository $gridcellRepository,
    )
    {
    }

    public function setFromRowValues(mixed $values): void
    {
        if (!is_array($values)) {
            return;
        }
        $allCols = $this->getGeoguessrData->allColsById();
        $row = $this->getGeoguessrData->getEmptyCountryRow();
        foreach ($values as $colId => $value) {
            if (!isset($allCols[$colId])) {
                continue;
            }
            $col = $allCols[$colId];
            $value = implode("\r\n", $this->explode($value, ['|']));
            $this->setCellvalue($row, $col, $value);
        }
    }

    public function spreadWildcard(mixed $wildcard): string
    {
        $colsByName = $this->getGeoguessrData->allColsByName();
        if (!is_string($wildcard)) {
            return '';
        }
        $unspread = [];
        $values = $this->explode($wildcard);
        foreach ($values as $valuePrefixed) {
            $valuePrefixedUnicode = new UnicodeString($valuePrefixed);
            if (!$valuePrefixedUnicode->containsAny(self::COL_SEPARATOR)) {
                $unspread[] = $valuePrefixed;
                continue;
            }
            $valuePart = $valuePrefixedUnicode->after(self::COL_SEPARATOR)->toString();
            if ($valuePart === '') {
                $unspread[] = $valuePrefixed;
                continue;
            }
            $colnamePart = $valuePrefixedUnicode->before(self::COL_SEPARATOR)->toString();
            $cols = $this->colsWithNamepart($colsByName, $colnamePart);
            if (count($cols) !== 1) {
                $unspread[] = $valuePrefixed;
                continue;
            }
            $this->updateCellSingleValue($cols[0], $valuePart);
        }

        return implode("\r\n", $unspread);
    }

    public function updateCellSingleValue(Gridcol $col, string $value): void
    {
        $row = $this->getGeoguessrData->getEmptyCountryRow();
        if (!$row instanceof Gridrow) {
            return;
        }
        $cell = $this->getGeoguessrData->getCell($row, $col);
        if ($cell === null) {
            $cell = new Gridcell();
            $cell->setGridcol($col)->setGridrow($row)->setValue($value);
            $this->entityManager->persist($cell);
            $this->entityManager->flush();
            return;
        }
        $existingSingleValues = $this->explode($cell->getValue());
        if (!in_array($value, $existingSingleValues)) {
            $existingSingleValues[] = $value;
            $cell->setValue(implode("\r\n", $existingSingleValues));
            $this->entityManager->flush();
        }
    }

    /**
     * @param array<string, Gridcol> $cols
     * @return Gridcol[]
     */
    private function colsWithNamepart(array $cols, string $namePart): array
    {
        $hits = [];
        foreach ($cols as $colname => $col) {
            if (str_contains($colname, $namePart)) {
                $hits[] = $col;
            }
        }

        return $hits;
    }

    public function setWildcard(string $wildcard): void
    {
        $row = $this->getGeoguessrData->getEmptyCountryRow();
        if ($row === null) {
            $row = $this->newRow();
        }
        $this->setCellvalue($row, $this->getGeoguessrData->getWildcardCol(), $wildcard);
    }

    public function setCellvalue(?Gridrow $row, ?Gridcol $col, string $value): void
    {
        if ($row === null || $col === null) {
            return;
        }
        $cell = $this->gridcellRepository->findOneBy(['gridrow'=>$row, 'gridcol'=>$col]);
        if (!$cell instanceof Gridcell) {
            $cell = new Gridcell();
            $cell->setGridcol($col)->setGridrow($row);
        }
        if ($cell->getValue() !== $value) {
            $cell->setValue($value);
            $this->entityManager->persist($cell);
            $this->entityManager->flush();
        }
    }

    private function newRow(): ?Gridrow
    {
        $table = $this->getGeoguessrData->getTable();
        $countryCol = $this->getGeoguessrData->getCountryCol();
        if (!$table instanceof Gridtable || !$countryCol instanceof Gridcol) {
            return null;
        }
        $row = new Gridrow();
        $maxLinenumber = $this->getGeoguessrData->maxLinenumber();
        $row->setGridtable($table)->setLineNumber($maxLinenumber+1);
        $this->entityManager->persist($row);
        $this->setCellvalue($row, $this->getGeoguessrData->getCountryCol(), '');
        $this->setCellvalue($row, $this->getGeoguessrData->getAreaCol(), '');
        $this->entityManager->flush();

        return $row;
    }

}