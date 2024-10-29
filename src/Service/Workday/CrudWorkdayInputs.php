<?php

namespace App\Service\Workday;

use App\Entity\User;
use App\Entity\Workday;
use App\Repository\WorkdayRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CrudWorkdayInputs
{

    public function __construct(
        private WorkdayRepository $workdayRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function crudByInputs(array $inputs, User $user, DateTime $today): array
    {
        $counts = ['create' => 0, 'update' => 0, 'delete' => 0];
        if (!isset($inputs['entry'])) {
            return $counts;
        }

        $existingEntries = $this->workdayRepository->existingEntriesOfUserSince($user, $today);
        $existingEntriesByYmd = array_combine(
            array_map(fn ($wd) => $this->datetimeYmd($wd->getDay()), $existingEntries),
            $existingEntries
        );

        foreach ($inputs['entry'] as $dayYmd => $newEntry) {
            $existingEntry = $existingEntriesByYmd[$dayYmd] ?? null;
            $counts = $this->crudByEntry($dayYmd, $user, $existingEntry, $newEntry, $counts);
        }

        $this->entityManager->flush();

        return $counts;
    }

    private function crudByEntry(string $dayYmd, User $user, ?Workday $existingEntry, array $newEntry, array $counts): array
    {
        $option = $this->sanitizedOption($newEntry['option']); // none, away, office, home

        if ($option === null) {
            return $counts;
        }

        if ($existingEntry === null && $option === 'none') {
            return $counts;
        }

        if ($existingEntry !== null && $option === 'none') {
            $counts['delete'] += $this->delete($existingEntry);

            return $counts;
        }

        if ($existingEntry === null) {
            $counts['create'] += $this->create($user, $dayYmd, $option, $newEntry);
        } else {
            $counts['update'] += $this->update($existingEntry, $option, $newEntry);
        }


        return $counts;
    }

    private function delete(Workday $existingEntry): int
    {
        $this->entityManager->remove($existingEntry);

        return 1;
    }

    private function create(User $user, string $dayYmd, string $option, array $newEntry): int
    {
        $startHour = $this->sanitizeStartHour($newEntry['startHour'], $option);
        $workHours = $this->sanitizeWorkHours($newEntry['workHours'], $option);
        $day = DateTime::createFromFormat('Ymd', $dayYmd);

        $workday = new Workday();
        $workday
            ->setUser($user)
            ->setDay($day)
            ->setAway($option === 'away')
            ->setHomeoffice($option === 'home')
            ->setStartHour($startHour)
            ->setWorkHours($workHours)
        ;
        $this->entityManager->persist($workday);

        return 1;
    }

    private function update(Workday $existingEntry, string $option, array $newEntry): int
    {
        $startHour = $this->sanitizeStartHour($newEntry['startHour'], $option);
        $workHours = $this->sanitizeWorkHours($newEntry['workHours'], $option);

        if ($this->isNoChange($existingEntry, $option, $startHour, $workHours) === true) {
            return 0;
        }

        $existingEntry
            ->setAway($option === 'away')
            ->setHomeoffice($option === 'home')
            ->setStartHour($startHour)
            ->setWorkHours($workHours)
            ->setSuperAway(false)
        ;
        $this->entityManager->persist($existingEntry);

        return 1;
    }

    private function isNoChange(Workday $existingEntry, string $option, ?int $startHour, ?int $workHours): bool
    {
        if ($existingEntry->isSuperAway() === true) {
            return false;
        }
        if ($existingEntry->isAway() !== ($option === 'away')) {
            return false;
        }
        if ($existingEntry->isHomeoffice() !== ($option === 'home')) {
            return false;
        }
        if ($existingEntry->getStartHour() !== $startHour) {
            return false;
        }
        if ($existingEntry->getWorkHours() != $workHours) {
            return false;
        }

        return true;
    }

    private function sanitizedOption(string $option): ?string
    {
        return (in_array($option, ['none', 'away', 'office', 'home'])) ? $option : null;
    }

    private function sanitizeStartHour(string $startHour, string $option): ?int
    {
        if (in_array($option, ['none', 'away'])) {
            return null;
        }
        $int = intval($startHour);
        return ($int >= 6 && $int <= 18) ? $int : null;
    }

    private function sanitizeWorkHours(string $workHours, string $option): ?int
    {
        if (in_array($option, ['none', 'away'])) {
            return null;
        }
        $int = intval($workHours);
        return ($int >= 1 && $int <= 10) ? $int : null;
    }

    private function datetimeYmd(?DateTimeInterface $dateTime): string
    {
        return ($dateTime instanceof DateTime) ? $dateTime->format('Ymd') : '';
    }

}