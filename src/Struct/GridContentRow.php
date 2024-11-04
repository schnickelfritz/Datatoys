<?php

namespace App\Struct;

use App\Entity\Gridrow;

class GridContentRow
{
    public function __construct(
        public Gridrow $row,
        /** @var array<string, string|null> */
        public array $cells,
    )
    {
    }
}