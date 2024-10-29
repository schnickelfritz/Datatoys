<?php

namespace App\Controller\Grid;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

#[AsController]
#[IsGranted('ROLE_GRIDADMIN')]
#[Route('/grid/scope/create', name: 'app_grid_scope_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class GridScopeCreateController
{

    public function __construct(
        private Environment             $twig,

    ) {
    }

    public function __invoke(Request $request): Response
    {
        return new Response($this->twig->render('grid/scope/create.html.twig', [

        ]));
    }
}