<?php

declare(strict_types=1);

namespace App\Controller\Grid;

use App\Entity\Gridpool;
use App\Form\Grid\GridpoolFormType;
use App\Repository\GridpoolRepository;
use App\Service\Grid\CreateGridpool;
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
#[Route('/grid/pool/create', name: 'app_grid_pool_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class GridpoolCreateController
{
    /*
     * A Gridpool is a collection of GridRows.
     * Each GridRow is a collection of GridCells.
     * Each GridCell is linked to a GridRow and a GridCol.
     */
    use FlashMessageTrait;

    public function __construct(
        private CreateGridpool         $createPool,
        private FormFactoryInterface   $formFactory,
        private UrlGeneratorInterface  $urlGenerator,
        private GridpoolRepository     $poolRepository,
        private Environment            $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $pool = new Gridpool();
        $form = $this->formFactory->create(GridpoolFormType::class, $pool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->createPool->create($pool);
            $this->addFlash($request, 'success', 'flash.success.create');

            return new RedirectResponse($this->urlGenerator->generate('app_grid_pool_create'));
        }

        $pools = $this->poolRepository->allPoolsFiltered();
        return new Response($this->twig->render('grid/gridpool_create.html.twig', [
            'form_pool' => $form->createView(),
            'pools' => $pools,

        ]));
    }
}
