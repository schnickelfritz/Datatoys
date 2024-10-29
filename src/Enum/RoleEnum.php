<?php

namespace App\Enum;

enum RoleEnum: string
{
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_GRIDADMIN = 'ROLE_GRIDADMIN';
    case ROLE_USERMANAGER = 'ROLE_USERMANAGER';
    case ROLE_WORKTIME_PLANNER = 'ROLE_WORKTIME_PLANNER';

    public function label(): string
    {
        return match ($this) {
            self::ROLE_ADMIN => 'roles.admin',
            self::ROLE_GRIDADMIN => 'roles.gridadmin',
            self::ROLE_USERMANAGER => 'roles.user_manager',
            self::ROLE_WORKTIME_PLANNER => 'roles.worktime_planner_user',
        };
    }

    /**
     * @return array<string>
     */
    public static function all(): array
    {
        $r = [];
        foreach (self::cases() as $case) {
            $r[$case->label()] = $case->value;
        }

        return $r;
    }

}
