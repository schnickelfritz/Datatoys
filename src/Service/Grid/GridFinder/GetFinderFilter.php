<?php

namespace App\Service\Grid\GridFinder;

use App\Enum\UserSettingEnum;
use App\Service\UserSetting\GetUserSetting;

final readonly class GetFinderFilter
{

    public function __construct(
        private GetUserSetting $getUserSetting,

    )
    {
    }

    public function getFilter(): string
    {
        $filter = $this->getUserSetting->getSetting(UserSettingEnum::GRIDFINDER_FILTER);
        return $filter === null ? '' : $filter;
    }

}