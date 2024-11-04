<?php

declare(strict_types=1);

namespace App\Controller\Grid\Gridrow;

use App\Entity\Gridrow;
use App\Repository\GridcellRepository;
use App\Repository\GridtableRepository;
use App\Service\Grid\MapGridrowsContent;
use App\Service\Grid\UpdateGridrow;
use App\Trait\FlashMessageTrait;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Webmozart\Assert\Assert;

#[AsController]
#[IsGranted('ROLE_GRIDADMIN')]
#[Route('/grid/row/update/{id}', name: 'app_grid_row_update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class GridrowUpdateController
{
    use FlashMessageTrait;

    public function __construct(
        private FormFactoryInterface $formFactory,
        private GridtableRepository $tableRepository,
        private GridcellRepository $cellRepository,
        private MapGridrowsContent $mapGridrowsContent,
        private UpdateGridrow $updateGridrow,
        private UrlGeneratorInterface $urlGenerator,
        private TranslatorInterface $translator,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request, Gridrow $row): Response
    {
        $table = $row->getGridtable();
        $mappedRows = $this->mapGridrowsContent->mapRows($table);
        $cells = $this->cellRepository->findBy(['gridrow'=>$row]);
        if ($request->isMethod('POST')) {
            $cellValues = $request->get('cellvalue');
            Assert::isArray($cellValues);
            $numberOfChanges = $this->updateGridrow->updateCells($row, $cellValues);
            $this->addFlash($request, 'success', $this->translator->trans('flash.success.updates', ['number' => $numberOfChanges]));

            return new RedirectResponse($this->urlGenerator->generate('app_grid_row_update', ['id' => $row->getId()]));
        }

        return new Response(
            $this->twig->render('grid/row/gridrow_update.html.twig', [
                'cells' => $cells,
                'mapped_rows' => $mappedRows,
                'table_selected' => $table,
                'row_selected' => $row,
            ])
        );
    }
}
