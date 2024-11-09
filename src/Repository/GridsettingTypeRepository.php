<?php

namespace App\Repository;

use App\Model\GridsettingType;

class GridsettingTypeRepository
{

    /**
     * @return GridsettingType[]
     */
    public function findAll(): array
    {
        return [
            new GridsettingType('SORTINDEX', 'grid.setting_type.label.sortindex', 'float'),
            new GridsettingType('REQUIRED', 'grid.setting_type.label.required', 'none'),
            new GridsettingType('TYPE_TEXT', 'grid.setting_type.label.type_text', 'none'),
            new GridsettingType('TYPE_MULTIVALUE', 'grid.setting_type.label.type_multivalue', 'none'),
            new GridsettingType('TYPE_FILENAME', 'grid.setting_type.label.type_filename', 'none'),
            new GridsettingType('TYPE_EAN_LIST', 'grid.setting_type.label.type_ean_list', 'none'),
            new GridsettingType('PREDEFINED_VALUES', 'grid.setting_type.label.predifined_values', 'string'),
            new GridsettingType('UNIT', 'grid.setting_type.label.unit', 'string'),
            new GridsettingType('DESCRIPTION', 'grid.setting_type.label.description', 'string'),
            new GridsettingType('GROUPNAME', 'grid.setting_type.label.groupname', 'string'),
            new GridsettingType('REQUIRED_IN_GROUP', 'grid.setting_type.label.required_in_group', 'none'),
            new GridsettingType('TEXTLENGTH_MIN', 'grid.setting_type.label.textlength_min', 'int'),
            new GridsettingType('TEXTLENGTH_MAX', 'grid.setting_type.label.textlength_max', 'int'),
            new GridsettingType('IMAGEWIDTH_MIN', 'grid.setting_type.label.imagewidth_min', 'int'),
            new GridsettingType('IMAGEWIDTH_MAX', 'grid.setting_type.label.imagewidth_max', 'int'),
            new GridsettingType('IMAGEHEIGHT_MIN', 'grid.setting_type.label.imageheight_min', 'int'),
            new GridsettingType('IMAGEHEIGHT_MAX', 'grid.setting_type.label.imageheight_max', 'int'),
            new GridsettingType('FILESIZE_MAX', 'grid.setting_type.label.filesize_max', 'int'),
        ];
    }


    public function choices(): array
    {
        $types = [];
        foreach ($this->findAll() as $type) {
            $types[$type->getLabel()] = $type->getName();
        }

        return $types;
    }

    public function allByName(): array
    {
        $types = [];
        foreach ($this->findAll() as $type) {
            $types[$type->getName()] = $type;
        }

        return $types;
    }

    public function oneByName(string $name): ?GridsettingType
    {
        foreach ($this->findAll() as $type) {
            if ($type->getName() === $name) {
                return $type;
            }
        }

        return null;
    }
}