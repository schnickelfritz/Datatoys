<?php

declare(strict_types=1);

namespace App\Controller\Grid;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

#[AsController]
#[IsGranted('ROLE_GRIDADMIN')]
#[Route('/grid/grid/create', name: 'app_grid_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class GridCreateController
{
    /*
     * A Grid is a collection of GridRows.
     * Each GridRow is a collection of GridCells.
     * Each GridCell is linked to a GridRow and a GridColumn.
     */

    public function __construct(
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        return new Response($this->twig->render('grid/grid_create.html.twig', [
        ]));
    }
}
