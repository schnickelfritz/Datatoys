<?php

namespace App\Service\Grid\Gridfile;

use App\Entity\Gridfile;

class FindGridfileByCellvalue
{

    public function findFileByCellvalue(string $value, array $filesNameKeyed): ?Gridfile
    {
        $value = strtolower($value);
        if (isset($filesNameKeyed[$value])) {
            return $filesNameKeyed[$value];
        }

        $valueWithoutSuffix = (str_contains($value, '.')) ? substr($value, 0, strrpos($value, '.')) : $value;
        if (isset($filesNameKeyed[$valueWithoutSuffix])) {
            return $filesNameKeyed[$valueWithoutSuffix];
        }
        
        foreach (array_keys($filesNameKeyed) as $filename) {
            if (str_starts_with($filename, $valueWithoutSuffix . '.')) {
                return $filesNameKeyed[$filename];
            }
        }

        return null;
    }

}