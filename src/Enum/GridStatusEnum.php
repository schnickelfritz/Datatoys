<?php

declare(strict_types=1);

namespace App\Enum;

enum GridStatusEnum: string
{
    case NEW = 'NEW';
    case IN_PROGRESS = 'IN_PROGRESS';
    case DONE = 'DONE';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'grid.table.status.new',
            self::IN_PROGRESS => 'grid.table.status.in_progress',
            self::DONE => 'grid.table.status.done',
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
