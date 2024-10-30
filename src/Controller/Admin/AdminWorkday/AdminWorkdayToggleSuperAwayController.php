<?php

declare(strict_types=1);

namespace App\Controller\Admin\AdminWorkday;

use App\Entity\Workday;
use App\Trait\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
#[IsGranted('ROLE_USERMANAGER')]
#[Route('/admin/planner/super-away-toggle/{id}', name: 'app_admin_planner_away_toggle', methods: [Request::METHOD_POST])]
final readonly class AdminWorkdayToggleSuperAwayController
{
    use FlashMessageTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(Request $request, Workday $workday): Response
    {
        if ($workday->isAway() === false) {
            if ($workday->isSuperAway() === true) {
                $workday->setSuperAway(false);
            } else {
                $workday->setSuperAway(true);
            }
            $this->entityManager->flush();
            $this->addFlash($request, 'success', 'flash.success.update');
        }

        return new RedirectResponse($this->urlGenerator->generate('app_planner_all_entries'));
    }
}
