<?php

declare(strict_types=1);

namespace App\Controller\Grid;

use App\Entity\Gridcol;
use App\Form\Grid\GridcolUpdateFormType;
use App\Repository\GridcolRepository;
use App\Repository\GridscopeColRepository;
use App\Service\Grid\CreateGridscopeCols;
use App\Service\Grid\DeleteGridscopeCols;
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
#[Route('/grid/col/update/{id}', name: 'app_grid_col_update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class GridcolUpdateController
{
    use FlashMessageTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GridscopeColRepository $gridscopeColRepository,
        private GridcolRepository $colRepository,
        private DeleteGridscopeCols $deleteGridscopeCols,
        private CreateGridscopeCols $createGridscopeCols,
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $urlGenerator,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request, Gridcol $col): Response
    {
        $scopes = $this->gridscopeColRepository->scopesByCol($col);
        $form = $this->formFactory->create(GridcolUpdateFormType::class, $col, ['selected_scopes'=>$scopes]);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $cols = $this->colRepository->allColumnsFiltered();

            return new Response(
                $this->twig->render('grid/gridcol_update.html.twig', [
                    'form_col' => $form->createView(),
                    'columns' => $cols,
                    'col_selected' => $col,
                ])
            );
        }
        $this->entityManager->persist($col);
        $this->entityManager->flush();

        $scopesNew = $form->get('scopes')->getData();
        if (is_array($scopesNew)) {
            $this->deleteGridscopeCols->deleteMultiple($col->getName());
            $this->createGridscopeCols->createMultiple($col->getName(), $scopesNew);
        }

        $this->addFlash($request, 'success', 'flash.success.update');

        return new RedirectResponse($this->urlGenerator->generate('app_grid_col_update', ['id' => $col->getId()]));
    }
}
