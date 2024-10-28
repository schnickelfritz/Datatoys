<?php

namespace App\Controller\Workday;


use App\Trait\FlashMessageTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
#[Route('/planner/edit', 'app_planner_edit', methods: [Request::METHOD_POST])]
#[IsGranted('ROLE_WORKTIME_PLANNER')]
final readonly class WorkdayEntriesEditController
{
    use FlashMessageTrait;

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    public function __invoke(Request $request): Response
    {
        $this->addFlash($request, 'info', 'flash.info.not_working_yet');
        return new RedirectResponse($this->urlGenerator->generate('app_workdays_entries'));
    }
}