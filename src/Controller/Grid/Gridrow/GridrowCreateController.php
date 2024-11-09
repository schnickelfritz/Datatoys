<?php

namespace App\Controller\Grid\Gridrow;

use App\Entity\Gridrow;
use App\Entity\Gridtable;
use App\Repository\GridscopeColRepository;
use App\Service\Grid\CreateGridrow;
use App\Service\Grid\MapGridrowsContent;
use App\Trait\FlashMessageTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;
use Webmozart\Assert\Assert;

#[AsController]
#[IsGranted('ROLE_GRIDADMIN')]
#[Route('/grid/row/create/{id}', name: 'app_grid_row_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class GridrowCreateController
{

    use FlashMessageTrait;

    public function __construct(
        private CreateGridrow $createGridrow,
        private MapGridrowsContent $mapGridrowsContent,
        private GridscopeColRepository $gridscopeColRepository,
        private UrlGeneratorInterface $urlGenerator,
        private Environment $twig,
    )
    {
    }

    public function __invoke(Request $request, Gridtable $table): Response
    {
        $scope = $table->getScope();
        $cols = $this->gridscopeColRepository->allColsInScope($scope);
        $mappedRows = $this->mapGridrowsContent->mapRows($table);
        if (!$request->isMethod('POST')) {
            return new Response(
                $this->twig->render('grid/row/gridrow_create.html.twig', [
                    'mapped_rows' => $mappedRows,
                    'table_selected' => $table,
                    'columns' => $cols,
                ])
            );
        }

        $cellValues = $request->get('cellvalue');
        if ($cellValues === null) {
            $this->addFlash($request, 'fail', 'flash.fail.inputs_missing');
            return new RedirectResponse($this->urlGenerator->generate('app_grid_row_create'));
        }

        Assert::isArray($cellValues);
        $row = $this->createGridrow->createByValues($table, $cellValues);
        if ($row instanceof Gridrow) {
            $this->addFlash($request, 'success', 'flash.success.done');
            return new RedirectResponse($this->urlGenerator->generate('app_grid_row_update', ['id' => $row->getId()]));
        }

        $this->addFlash($request, 'fail', 'flash.fail.invalid_inputs');
        return new RedirectResponse($this->urlGenerator->generate('app_grid_row_create', ['id' => $table->getId()]));

    }
}