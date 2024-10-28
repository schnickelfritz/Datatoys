<?php

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
#[Route('/planner/all-entries', 'app_planner_all_entries', methods: [Request::METHOD_GET])]
#[IsGranted('ROLE_WORKTIME_PLANNER')]
final readonly class WorkdayAllEntriesController
{

    public function __construct(
        private WorkdayRepository $workdayRepository,
        private Environment $twig,
    )
    {
    }

    public function __invoke(Request $request): Response
    {
        $today = new DateTime('today');
        $existingEntries = $this->workdayRepository->existingEntriesOfAllUsersSince($today);

        return new Response($this->twig->render('planner/all_entries.html.twig', [
            'existing_entries' => $existingEntries,
        ]));
    }


}