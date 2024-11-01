<?php

namespace App\Controller\Grid;

use App\Entity\Gridtable;
use App\Form\Grid\GridContentCreateFormType;
use App\Repository\GridtableRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

#[AsController]
#[IsGranted('ROLE_GRIDADMIN')]
#[Route('/grid/content/create/{id}', name: 'app_grid_tablecontent_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class GridContentCreateController
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $urlGenerator,
        private GridtableRepository $tableRepository,
        private Environment $twig,
    )
    {
    }

    public function __invoke(Request $request, Gridtable $table): Response
    {
        $form = $this->formFactory->create(GridContentCreateFormType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $tables = $this->tableRepository->alltablesFiltered();

            return new Response(
                $this->twig->render('grid/gridcontent_create.html.twig', [
                    'form_content' => $form->createView(),
                    'tables' => $tables,
                    'table_selected' => $table,
                ])
            );
        }

        return new RedirectResponse($this->urlGenerator->generate('app_grid_tablecontent_create', ['id' => $table->getId()]));
    }
}