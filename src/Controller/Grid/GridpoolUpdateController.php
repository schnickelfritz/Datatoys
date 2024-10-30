<?php

declare(strict_types=1);

namespace App\Controller\Grid;

use App\Entity\Gridpool;
use App\Form\Grid\GridpoolFormType;
use App\Form\Grid\GridscopeFormType;
use App\Repository\GridpoolRepository;
use App\Repository\GridscopeRepository;
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
#[Route('/grid/pool/update/{id}', name: 'app_grid_pool_update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class GridpoolUpdateController
{
    use FlashMessageTrait;
    use FormStringValueTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GridpoolRepository $poolRepository,
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $urlGenerator,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request, Gridpool $pool): Response
    {
        $checkSumBeforeUpdate = $pool->getChecksum();
        $form = $this->formFactory->create(GridpoolFormType::class, $pool);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $pools = $this->poolRepository->allPoolsFiltered();

            return new Response(
                $this->twig->render('grid/gridpool_update.html.twig', [
                    'form_pool' => $form->createView(),
                    'pools' => $pools,
                    'pool_selected' => $pool,
                ])
            );
        }
        $this->entityManager->persist($pool);
        $this->entityManager->flush();

        if ($checkSumBeforeUpdate === $pool->getChecksum()) {
            $this->addFlash($request, 'info', 'flash.info.no_changes_found');
        } else {
            $this->addFlash($request, 'success', 'flash.success.update');
        }

        return new RedirectResponse($this->urlGenerator->generate('app_grid_pool_update', ['id' => $pool->getId()]));
    }
}
