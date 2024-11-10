<?php

namespace App\Service\Grid;

use App\Entity\Gridcol;
use App\Entity\Gridscope;
use App\Entity\Gridsetting;
use App\Model\GridsettingType;
use App\Repository\GridsettingRepository;
use App\Repository\GridsettingTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class CreateGridsettings
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GridsettingTypeRepository $gridsettingTypeRepository,
        private GridsettingRepository $gridsettingRepository,
        private TranslatorInterface $translator,
    )
    {
    }

    /**
     * @return string[]|null
     */
    public function createSettings(Gridscope $scope, mixed $columns, mixed $settingKey, mixed $parameter): ?array
    {
        $columns = $this->getColumns($columns);
        $type = $this->getSettingType($settingKey);
        $parameter = $this->getParameter($parameter);

        if ($columns === null || $type === null) {
            return $this->errors($columns, $type);
        }
        foreach ($columns as $col) {
            $this->createByColumn($scope, $col, $type, $parameter);
        }
        $this->entityManager->flush();

        return null;
    }

    /**
     * @param Gridcol[] $columns
     * @param GridsettingType|null $type
     * @return string[]
     */
    private function errors(?array $columns, ?GridsettingType $type): array
    {
        $errors = [];
        if ($columns === null) {
            $errors[] = $this->translator->trans('grid.setting.error.no_column_found');
        }
        if (!$type instanceof GridsettingType) {
            $errors[] = $this->translator->trans('grid.setting.error.no_type_found');
        }
        return $errors;
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