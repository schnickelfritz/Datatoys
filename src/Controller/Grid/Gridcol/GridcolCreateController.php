<?php

declare(strict_types=1);

namespace App\Controller\Grid\Gridcol;

use App\Form\Grid\GridcolsCreateFormType;
use App\Repository\GridscopeRepository;
use App\Service\Grid\CreateGridcols;
use App\Service\Grid\CreateGridscopeCols;
use App\Service\Grid\GetFilteredGridcols;
use App\Trait\FlashMessageTrait;
use App\Trait\FormStringValueTrait;
use Symfony\Component\Form\FormFactoryInterface;
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

    use FlashMessageTrait;
    use FormStringValueTrait;

    public function __construct(
        private CreateGridcols $createGridcols,
        private GridscopeRepository $gridscopeRepository,
        private CreateGridscopeCols $createGridscopeCols,
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $urlGenerator,
        private GetFilteredGridcols $filteredGridcols,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(GridcolsCreateFormType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $columns = $this->filteredGridcols->getCols();
            $filter = $this->filteredGridcols->getFilter();
            $allScopes = $this->gridscopeRepository->findAll();

            return new Response($this->twig->render('grid/col/gridcol_create.html.twig', [
                'form_cols' => $form->createView(),
                'columns' => $columns,
                'columns_filter' => $filter,
                'scopes' => $allScopes,
            ]));
        }

        $names = $this->formStringValue($form, 'names');
        Assert::string($names);
        $this->createGridcols->createMultiple($names);

        $scopes = $form->get('scopes')->getData();
        if (is_array($scopes)) {
            $this->createGridscopeCols->createMultiple($names, $scopes);
        }

        $this->addFlash($request, 'success', 'flash.success.create');

        return new RedirectResponse($this->urlGenerator->generate('app_grid_col_create'));
    }

}
