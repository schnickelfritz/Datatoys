<?php

namespace App\Controller\Grid\Gridrow;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
#[IsGranted('ROLE_GRIDADMIN')]
#[Route('/grid/row/multi-edit', name: 'app_grid_row_mulitedit', methods: [Request::METHOD_POST])]
final readonly class GridrowMultipleEditController
{

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    public function __invoke(Request $request): Response
    {

        dd($request);
        $referer = $request->headers->get('referer');
        if ($referer !== null) {
            return new RedirectResponse($referer);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_grid_finder'));

    }
}