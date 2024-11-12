<?php

declare(strict_types=1);

namespace App\Controller\Grid\Gridtable;

use App\Entity\Gridtable;
use App\Form\Grid\GridtableFormType;
use App\Repository\GridtableRepository;
use App\Service\Grid\Gridtable\CreateGridtable;
use App\Trait\FlashMessageTrait;
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
#[Route('/grid/table/create', name: 'app_grid_table_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class GridtableCreateController
{

    use FlashMessageTrait;

    public function __construct(
        private CreateGridtable $createtable,
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $urlGenerator,
        private GridtableRepository $tableRepository,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $table = new Gridtable();
        $form = $this->formFactory->create(GridtableFormType::class, $table);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->createtable->create($table);
            $this->addFlash($request, 'success', 'flash.success.create');

            return new RedirectResponse($this->urlGenerator->generate('app_grid_table_create'));
        }

        $tables = $this->tableRepository->alltablesFiltered();

        return new Response($this->twig->render('grid/table/gridtable_create.html.twig', [
            'form_table' => $form->createView(),
            'tables' => $tables,
        ]));
    }
}
