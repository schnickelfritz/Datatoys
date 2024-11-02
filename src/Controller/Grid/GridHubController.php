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
#[Route('/grid/hub', name: 'app_grid_hub', methods: [Request::METHOD_GET])]
final readonly class GridHubController
{
    public function __construct(
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        return new Response($this->twig->render('grid/hub.html.twig', [
        ]));
    }
}
