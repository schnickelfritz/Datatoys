<?php

declare(strict_types=1);

namespace App\Controller\Grid;

use App\Entity\Gridscope;
use App\Form\Grid\GridcolsCreateFormType;
use App\Repository\GridcolRepository;
use App\Service\Grid\CreateGridcols;
use App\Service\Grid\CreateGridscopeCols;
use App\Trait\FlashMessageTrait;
use App\Trait\FormStringValueTrait;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
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
#[Route('/grid/col/create', name: 'app_grid_col_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class GridcolCreateController
{
    /*
     * A GridCol is like a column in a matrix or like a property (name).
     */

    use FlashMessageTrait;
    use FormStringValueTrait;

    public function __construct(
        private CreateGridcols $createGridcols,
        private CreateGridscopeCols $createGridscopeCols,
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $urlGenerator,
        private GridcolRepository $colRepository,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(GridcolsCreateFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newCols = $this->createGridcols->createMultiple($this->formStringValue($form, 'names'));
            $this->linkNewColsToScope($form, $newCols);
            $this->addFlash($request, 'success', 'flash.success.create');

            return new RedirectResponse($this->urlGenerator->generate('app_grid_col_create'));
        }

        $columns = $this->colRepository->allColumnsFiltered();

        return new Response($this->twig->render('grid/gridcol_create.html.twig', [
            'form_cols' => $form->createView(),
            'columns' => $columns,
        ]));
    }

    private function linkNewColsToScope(FormInterface $form): void
    {
        $doLinkColsToScope = $form->get('linkColsToScope')->getData();
        Assert::boolean($doLinkColsToScope, 'should be boolean (from checkbox)');
        if ($doLinkColsToScope === false) {
            return;
        }
        $scope = $form->get('scope')->getData();
        if (!$scope instanceof Gridscope) {
            return;
        }
        $this->createGridscopeCols->createMultiple($this->formStringValue($form, 'names'), $scope);
    }
}
