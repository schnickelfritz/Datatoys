<?php

namespace App\Controller\Grid\Gridcol;

use App\Service\Grid\GridscopeCols\CreateGridscopeColsByIds;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
#[IsGranted('ROLE_GRIDADMIN')]
#[Route('/grid/col/multi-update', name: 'app_grid_col_multiupdate', methods: [Request::METHOD_POST])]
final readonly class GridcolMultiUpdateController
{

    public function __construct(
        private CreateGridscopeColsByIds $createGridscopeColsByIds,
        private UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    public function __invoke(Request $request): Response
    {
        $selectedColIds = $request->get('table_col');
        $selectedScopeIds = $request->get('scopeselect');
        if (is_array($selectedColIds) && is_array($selectedScopeIds)) {
            $this->createGridscopeColsByIds->createMultipleByIds($selectedColIds, $selectedScopeIds);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_grid_col_create'));
    }
}