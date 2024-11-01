<?php

declare(strict_types=1);

namespace App\Controller\Grid;

use App\Entity\Gridtable;
use App\Form\Grid\GridtableFormType;
use App\Repository\GridtableRepository;
use App\Trait\FlashMessageTrait;
use App\Trait\FormStringValueTrait;
use Doctrine\ORM\EntityManagerInterface;
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
#[Route('/grid/table/update/{id}', name: 'app_grid_table_update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class GridtableUpdateController
{
    use FlashMessageTrait;
    use FormStringValueTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GridtableRepository $tableRepository,
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $urlGenerator,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request, Gridtable $table): Response
    {
        $checkSumBeforeUpdate = $table->getChecksum();
        $form = $this->formFactory->create(GridtableFormType::class, $table);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $tables = $this->tableRepository->alltablesFiltered();

            return new Response(
                $this->twig->render('grid/gridtable_update.html.twig', [
                    'form_table' => $form->createView(),
                    'tables' => $tables,
                    'table_selected' => $table,
                ])
            );
        }
        $this->entityManager->persist($table);
        $this->entityManager->flush();

        if ($checkSumBeforeUpdate === $table->getChecksum()) {
            $this->addFlash($request, 'info', 'flash.info.no_changes_found');
        } else {
            $this->addFlash($request, 'success', 'flash.success.update');
        }

        return new RedirectResponse($this->urlGenerator->generate('app_grid_table_update', ['id' => $table->getId()]));
    }
}
