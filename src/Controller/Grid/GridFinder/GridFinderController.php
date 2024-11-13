<?php

declare(strict_types=1);

namespace App\Controller\Grid\GridFinder;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

#[AsController]
#[IsGranted('ROLE_GRIDADMIN')]
#[Route('/grid/finder', name: 'app_grid_finder', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class GridFinderController
{
    public function __construct(
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        return new Response($this->twig->render('grid/finder/gridfinder.html.twig', [
        ]));
    }
}
