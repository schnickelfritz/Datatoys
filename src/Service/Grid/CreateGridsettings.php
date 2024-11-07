<?php

namespace App\Service\Grid;

use App\Entity\Gridcol;
use App\Entity\Gridscope;
use App\Entity\Gridsetting;
use App\Model\GridsettingType;
use App\Repository\GridsettingRepository;
use App\Repository\GridsettingTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CreateGridsettings
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GridsettingTypeRepository $gridsettingTypeRepository,
        private GridsettingRepository $gridsettingRepository,
    )
    {
    }

    /**
     * @return string[]|null
     */
    public function createSettings(Gridscope $scope, mixed $columns, mixed $settingKey, mixed $parameter): ?array
    {
        $errors = [];
        $columns = $this->getColumns($columns);
        if ($columns === null) {
            $errors[] = 'grid.settings.error.no_column_found';
        }
        $type = $this->getSettingType($settingKey);
        if (!$type instanceof GridsettingType) {
            $errors[] = 'grid.settings.error.no_type_found';
        }
        if (!empty($errors)) {
            return $errors;
        }

        $parameter = $this->getParameter($parameter);

        foreach ($columns as $col) {
            $this->createByColumn($scope, $col, $type, $parameter);
        }
        $this->entityManager->flush();

        return null;
    }

    private function createByColumn(Gridscope $scope, Gridcol $col, GridsettingType $type, ?string $parameter): void
    {
        $existing = $this->gridsettingRepository->findOneBy(['scope' => $scope, 'gridcol' => $col, 'settingKey' => $type->getName()]);
        if ($existing instanceof Gridsetting) {
            return;
        }
        $setting = new Gridsetting();
        $setting
            ->setScope($scope)
            ->setGridcol($col)
            ->setSettingKey($type->getName())
            ->setParameter($parameter)
        ;
        $this->entityManager->persist($setting);
    }

    /**
     * @return Gridcol[]|null
     */
    private function getColumns(mixed $columns): ?array
    {
        if (!is_array($columns)) {
            return null;
        }

        return $columns;
    }

    private function getSettingType(mixed $settingKey): ?GridsettingType
    {
        if (!is_string($settingKey)) {
            return null;
        }

        return $this->gridsettingTypeRepository->oneByName($settingKey);
    }

    private function getParameter(mixed $parameter): ?string
    {
        if (!is_string($parameter)) {
            return null;
        }

        return $parameter;
    }
}