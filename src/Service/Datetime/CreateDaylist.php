<?php

declare(strict_types=1);

namespace App\Service\Datetime;

use DateTime;

use function sprintf;

final readonly class CreateDaylist
{
    private const WEEKDAY_INDEX_MONDAY = 1;

    /**
     * @return array<int, DateTime>
     */
    public function daylistOfFullWeeks(DateTime $startDate, int $numberOfFullWeeks): array
    {
        $list = [];
        $nextFullWeekEnding = $this->nextFullWeekEnding($startDate, $numberOfFullWeeks);
        $next = clone $startDate;
        while ($next <= $nextFullWeekEnding) {
            $list[] = new DateTime($next->format('Y-m-d'));
            $next->modify('+1 day');
        }

        return $list;
    }

    public function nextFullWeekEnding(DateTime $startDate, int $numberOfFullWeeks): DateTime
    {
        $weekdayOfStartDate = (int) $startDate->format('N');
        $daysTillWeekEnding = 7 - $weekdayOfStartDate;
        $nextFullWeekEnding = clone $startDate;
        if ($weekdayOfStartDate === self::WEEKDAY_INDEX_MONDAY) {
            --$numberOfFullWeeks;
        }
        $daysToAdd = $daysTillWeekEnding + $numberOfFullWeeks * 7;
        $nextFullWeekEnding->modify(sprintf('+%d days', $daysToAdd));

        return $nextFullWeekEnding;
    }
}
