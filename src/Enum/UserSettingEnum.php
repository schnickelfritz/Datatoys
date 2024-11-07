<?php

namespace App\Enum;

enum UserSettingEnum: string
{
    case GRIDCOL_FILTER = 'GRIDCOLFILTER';
    case GRIDTABLE_FILTER = 'GRIDTABLEFILTER';
    case GRIDFINDER_FILTER = 'GRIDFINDERFILTER';
    case GRIDSCOPE_ID = 'GRIDSCOPE_ID';
    case GRIDTABLE_ID = 'GRIDTABLE_ID';
    case GRIDROW_ID = 'GRIDROW_ID';
}
