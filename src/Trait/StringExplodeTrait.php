<?php

declare(strict_types=1);

namespace App\Trait;

use function in_array;

trait StringExplodeTrait
{
    /**
     * @param string[] $delimiters
     *
     * @return string[]
     */
    protected function explode(?string $stringOfItems, array $delimiters = [' ', ',', ';', "\r", "\n", "\t"]): array
    {
        $items = [];
        if ($stringOfItems === null) {
            return $items;
        }

        $characters = mb_str_split($stringOfItems);
        $item = '';
        foreach ($characters as $character) {
            if (in_array($character, $delimiters)) {
                if ($item !== '') {
                    $items[] = $item;
                }
                $item = '';
            } else {
                $item .= $character;
            }
        }

        if ($item !== '') {
            $items[] = $item;
        }

        return $items;
    }
}
