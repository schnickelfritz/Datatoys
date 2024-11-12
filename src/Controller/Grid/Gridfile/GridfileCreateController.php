<?php

namespace App\Controller\Grid\Gridfile;

use App\Entity\Gridtable;
use App\Repository\GridfileRepository;
use App\Repository\GridtableRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

#[AsController]
#[IsGranted('ROLE_GRIDADMIN')]
#[Route('/grid/file/create/{id}', name: 'app_grid_file_create', methods: [Request::METHOD_GET])]
final readonly class GridfileCreateController
{

    public function __construct(
        private GridtableRepository $tableRepository,
        private GridfileRepository $gridfileRepository,
        private Environment $twig,
    )
    {
    }

    public function __invoke(Request $request, Gridtable $table): Response
    {
        $tables = $this->tableRepository->alltablesFiltered();
        $files = $this->gridfileRepository->findBy(['gridtable'=>$table]);

        return new Response(
            $this->twig->render('grid/file/file_create.html.twig', [
                'tables' => $tables,
                'files' => $files,
                'table_selected' => $table,
            ])
        );

    }
}