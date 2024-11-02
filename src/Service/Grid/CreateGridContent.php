<?php

declare(strict_types=1);

namespace App\Service\Grid;

use App\Entity\Gridtable;
use App\Service\Convert\ConvertCsvStringToMatrixArray;
use Webmozart\Assert\Assert;

use function count;
use function in_array;

final readonly class CreateGridContent
{
    public function __construct(
        private CreateGridcolsFromContent $createGridcolsFromContent,
        private CreateGridrowsFromContent $createGridrowsFromContent,
        private CreateGridcellsFromContent $createGridcellsFromContent,
    ) {
    }

    /**
     * @param list<string> $options
     */
    public function processInputs(Gridtable $table, string $content, string $separator, array $options): ?string
    {
        $converter = new ConvertCsvStringToMatrixArray();
        $matrix = $converter->toMatrix($content, $separator);
        if ($matrix === null) {
            return $converter->getError();
        }
        if (!isset($matrix[0])) {
            return 'grid.content.error.no_content';
        }

        $columnNames = $matrix[0];
        Assert::allString($columnNames);
        $columnNamesSanitized = array_map(fn (string $name) => strtolower(trim($name)), $columnNames);
        if ($this->illegalNonUniqueColnames($columnNamesSanitized, $options)) {
            return 'grid.content.error.colnames_not_unique';
        }

        $indexedGridColumns = $this->createGridcolsFromContent->getOrCreateGridcols($columnNamesSanitized, $options);
        if ($indexedGridColumns === null) {
            return 'grid.content.error.contains_new_cols';
        }

        $lineNumberMax = max(array_keys($matrix));
        $indexedGridrows = $this->createGridrowsFromContent->gridrowsCreateOrUpdate($table, $lineNumberMax, $options);
        $this->createGridcellsFromContent->gridcellsCreateOrUpdate($matrix, $indexedGridrows, $indexedGridColumns, $options);

        return null;
    }

    /**
     * @param array<int, string> $colNamesSanitized
     * @param list<string>       $options
     */
    private function illegalNonUniqueColnames(array $colNamesSanitized, array $options): bool
    {
        if (in_array('SKIP_SAME_NAME_COLUMNS', $options) || in_array('CONCAT_SAME_NAME_COLUMNS', $options)) {
            return false;
        }

        $countUnique = count(array_unique($colNamesSanitized));

        return $countUnique !== count($colNamesSanitized);
    }
}
