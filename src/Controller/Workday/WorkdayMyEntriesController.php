<?php

declare(strict_types=1);

namespace App\Controller\Workday;

use App\Entity\User;
use App\Repository\WorkdayRepository;
use App\Service\Datetime\MakeDaylist;
use App\Service\User\Me;
use DateTime;
use DateTimeInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

#[AsController]
#[Route('/planner/my-entries', 'app_planner_my_entries', methods: [Request::METHOD_GET])]
#[IsGranted('ROLE_WORKTIME_PLANNER')]
final readonly class WorkdayMyEntriesController
{
    public function __construct(
        private WorkdayRepository $workdayRepository,
        private UrlGeneratorInterface $urlGenerator,
        private MakeDaylist $makeDaylist,
        private Me $me,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $user = $this->me->user();
        if (!$user instanceof User) {
            return new RedirectResponse($this->urlGenerator->generate('app_home'));
        }

        $today = new DateTime('today');
        $daysForEntries = $this->makeDaylist->daylistOfFullWeeks($today, 3);
        $existingEntries = $this->workdayRepository->existingEntriesOfUserSince($user, $today);
        $existingEntriesByYmd = array_combine(
            array_map(fn ($wd) => $this->datetimeYmd($wd->getDay()), $existingEntries),
            $existingEntries
        );

        return new Response($this->twig->render('planner/my_entries.html.twig', [
            'calendar_days' => $daysForEntries,
            'existing_entries' => $existingEntriesByYmd,
        ]));
    }

    private function datetimeYmd(?DateTimeInterface $dateTime): string
    {
        return ($dateTime instanceof DateTime) ? $dateTime->format('Ymd') : '';
    }
}
