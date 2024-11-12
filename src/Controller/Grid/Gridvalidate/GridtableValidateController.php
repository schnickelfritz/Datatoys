<?php

namespace App\Controller\Grid\Gridvalidate;

use App\Entity\Gridtable;
use App\Service\Grid\GridValidate\ValidateGridtable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

#[AsController]
#[IsGranted('ROLE_GRIDADMIN')]
#[Route('/grid/validate/{id}', name: 'app_grid_validate_table', methods: [Request::METHOD_GET])]

final readonly class GridtableValidateController
{

    public function __construct(
        private ValidateGridtable $validateGridtable,
        private Environment $twig,
    )
    {
    }

    public function __invoke(Request $request, Gridtable $table): Response
    {
        $validationResults = $this->validateGridtable->validateTable($table);
        return new Response(
            $this->twig->render('grid/validate/table_validate_results.html.twig', [
                'validate_results' => $validationResults,
                'table_selected' => $table,
            ])
        );

    }
}