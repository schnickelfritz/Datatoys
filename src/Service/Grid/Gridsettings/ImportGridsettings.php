<?php

namespace App\Service\Grid\Gridsettings;

use App\Entity\Gridcol;
use App\Entity\Gridscope;
use App\Entity\Gridsetting;
use App\Model\GridsettingType;
use App\Repository\GridsettingRepository;
use App\Repository\GridsettingTypeRepository;
use App\Service\Grid\Gridcol\GetFilteredGridcols;
use App\Trait\StringExplodeTrait;
use Doctrine\ORM\EntityManagerInterface;

final readonly class ImportGridsettings
{

    use StringExplodeTrait;

    public function __construct(
        private GetFilteredGridcols       $filteredGridcols,
        private GridsettingRepository     $gridsettingRepository,
        private GridsettingTypeRepository $gridsettingTypeRepository,
        private EntityManagerInterface    $entityManager,
    )
    {
    }

    /**
     * @return string[]
     */
    public function processImportdata(string $importData, Gridscope $scope): array
    {
        $successfullyImportedLines = [];

        $allowedColumnsByName = $this->columnsByName($this->filteredGridcols->getCols($scope));
        $settingsByName = $this->gridsettingTypeRepository->allByName();

        $importLines = $this->explode($importData, ["\r", "\n"]);
        foreach ($importLines as $importLine) {
            $importColumns = $this->explode($importLine, ["\t", ";"]);

            $settingTypeName = $this->getSettingTypeName($importColumns, $settingsByName);
            $column = $this->getColumn($importColumns, $allowedColumnsByName);
            $parameter = $this->getParameter($importColumns, $settingsByName);

            if ($settingTypeName === null || $column === null || $parameter === false) {
                continue;
            }

            $existingSetting = $this->gridsettingRepository->findOneBy(['scope' => $scope, 'gridcol' => $column, 'settingKey' => $settingTypeName]);
            if ($existingSetting === null) {
                $setting = new Gridsetting();
                $setting->setScope($scope)->setGridcol($column)->setSettingKey($settingTypeName)->setParameter($parameter);
                $successfullyImportedLines[] = $importLine;
                $this->entityManager->persist($setting);
            } elseif ($existingSetting->getParameter() !== $parameter) {
                $existingSetting->setParameter($parameter);
                $successfullyImportedLines[] = $importLine;
                $this->entityManager->persist($existingSetting);
            }
        }
        $this->entityManager->flush();

        return $successfullyImportedLines;
    }

    private function getColumn(array $importColumns, array $allowedColumnsByName): ?Gridcol
    {
        $columnName = strtolower($importColumns[0]);
        if (!isset($allowedColumnsByName[$columnName])) {
            return null;
        }

        return $allowedColumnsByName[$columnName];
    }

    /**
     * @param string[] $importColumns
     * @param array<string, GridsettingType> $settingsByName
     */
    private function getSettingTypeName(array $importColumns, array $settingsByName): ?string
    {
        $settingType = $this->getSettingType($importColumns, $settingsByName);

        return $settingType?->getName();
    }

    /**
     * @param string[] $importColumns
     * @param array<string, GridsettingType> $settingsByName
     */
    private function getSettingType(array $importColumns, array $settingsByName): ?GridsettingType
    {
        if (count($importColumns) < 2) {
            return null;
        }
        $settingsName = $importColumns[1];
        if (!isset($settingsByName[$settingsName])) {
            return null;
        }

        return $settingsByName[$settingsName];
    }

    private function getParameter(array $importColumns, array $settingsByLabel): string|false|null
    {
        $settingType = $this->getSettingType($importColumns, $settingsByLabel);
        if ($settingType === null) {
            return false;
        }

        if ($settingType->getArgumentType() === 'none') {
            return null; // no parameter expected
        }

        if (count($importColumns) < 3) {
            return false;
        }

        return $importColumns[2];
    }

    /**
     * @param Gridcol[] $columns
     * @return array<string, Gridcol>
     */
    private function columnsByName(array $columns): array
    {
        $columnsByName = [];
        foreach ($columns as $column) {
            $name = $column->getName();

            $columnsByName[$name] = $column;
        }

        return $columnsByName;
    }

}