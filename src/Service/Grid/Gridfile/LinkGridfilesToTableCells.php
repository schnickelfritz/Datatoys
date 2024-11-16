<?php

namespace App\Service\Grid\Gridfile;

use App\Entity\Gridtable;
use App\Repository\GridfileRepository;
use App\Repository\GridrowRepository;
use App\Repository\GridsettingRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class LinkGridfilesToTableCells
{

    public function __construct(
        private FindGridfileByCellvalue $findGridfileByCellvalue,
        private GridfileRepository $gridfileRepository,
        private GridsettingRepository $gridsettingRepository,
        private GridrowRepository     $gridrowRepository,
        private EntityManagerInterface $entityManager,
        private RemoveFilelinksFromCells $removeFilelinksFromCells,
    )
    {
    }

    public function linkToCells(Gridtable $table): array
    {
        $rows = $this->gridrowRepository->allByTable($table);
        $scope = $table->getScope();
        $settings = $this->gridsettingRepository->allByScope($scope);

        $fileColIds = [];
        foreach ($settings as $setting) {
            $key = $setting->getSettingKey();
            if ($key === 'TYPE_FILENAME') {
                $settingColId = $setting->getGridcol()->getId();
                $fileColIds[] = $settingColId;
            }
        }

        if (empty($fileColIds)) {
            $this->removeFilelinksFromCells->removeFilelinksFromCells($table);
            return [];
        }

        $filesNameKeyed = $this->gridfileRepository->existingFilesByName($table);
        $linkedFiles = [];

        foreach ($rows as $row) {
            foreach ($row->getGridcells() as $cell) {
                $colId = $cell->getGridcol()->getId();
                $cellValue = $cell->getValue();
                if (!in_array($colId, $fileColIds) || $cellValue === '' || $cellValue === null) {
                    $cell->setGridfile(null);
                    continue;
                }
                $gridfile = $this->findGridfileByCellvalue->findFileByCellvalue($cellValue, $filesNameKeyed);
                if ($gridfile !== null) {
                    $linkedFiles[] = $gridfile->getOriginalName();
                }
                $cell->setGridfile($gridfile);
            }
        }

        $this->entityManager->flush();

        return array_unique($linkedFiles);
    }



}