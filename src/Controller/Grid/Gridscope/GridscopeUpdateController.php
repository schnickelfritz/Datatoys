<?php

declare(strict_types=1);

namespace App\Controller\Grid\Gridscope;

use App\Entity\Gridscope;
use App\Form\Grid\GridscopeFormType;
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
#[Route('/grid/scope/update/{id}', name: 'app_grid_scope_update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class GridscopeUpdateController
{
    use FlashMessageTrait;
    use FormStringValueTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private GridscopeRepository $scopeRepository,
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $urlGenerator,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request, Gridscope $scope): Response
    {
        $checkSumBeforeUpdate = $scope->getChecksum();
        $form = $this->formFactory->create(GridscopeFormType::class, $scope);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $scopes = $this->scopeRepository->findAll();

            return new Response(
                $this->twig->render('grid/scope/gridscope_update.html.twig', [
                    'form_scope' => $form->createView(),
                    'scopes' => $scopes,
                    'scope_selected' => $scope,
                ])
            );
        }
        $scope->setScopeKey(strtoupper($this->formStringValue($form, 'scopeKey')));
        $this->entityManager->persist($scope);
        $this->entityManager->flush();

        if ($checkSumBeforeUpdate === $scope->getChecksum()) {
            $this->addFlash($request, 'info', 'flash.info.no_changes_found');
        } else {
            $this->addFlash($request, 'success', 'flash.success.update');
        }

        return new RedirectResponse($this->urlGenerator->generate('app_grid_scope_update', ['id' => $scope->getId()]));
    }
}
