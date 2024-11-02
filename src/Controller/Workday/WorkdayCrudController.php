<?php

declare(strict_types=1);

namespace App\Controller\Workday;

use App\Entity\User;
use App\Service\User\Me;
use App\Service\Workday\CrudWorkdayInputs;
use App\Trait\FlashMessageTrait;
use DateTime;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsController]
#[Route('/planner/edit', 'app_planner_crud', methods: [Request::METHOD_POST])]
#[IsGranted('ROLE_WORKTIME_PLANNER')]
final readonly class WorkdayCrudController
{
    use FlashMessageTrait;

    public function __construct(
        private Me $me,
        private CrudWorkdayInputs $crudWorkdayInputs,
        private UrlGeneratorInterface $urlGenerator,
        private TranslatorInterface $translator,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $user = $this->me->user();
        if (!$user instanceof User) {
            return new RedirectResponse($this->urlGenerator->generate('app_home'));
        }

        $inputs = $request->request->all();
        $today = new DateTime('today');
        $counts = $this->crudWorkdayInputs->crudByInputs($inputs, $user, $today);
        $this->flash($request, $counts);

        return new RedirectResponse($this->urlGenerator->generate('app_planner_my_entries'));
    }

    /**
     * @param array{'create':int, 'update':int, 'delete':int} $counts
     */
    private function flash(Request $request, array $counts): void
    {
        $sum = array_sum($counts);
        if ($sum <= 0) {
            $this->addFlash($request, 'info', 'flash.info.no_changes_found');
        } else {
            $this->addFlash($request, 'success', $this->translator->trans('planner.crud_flash', $counts));
        }
    }
}
