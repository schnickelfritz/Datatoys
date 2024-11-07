<?php

declare(strict_types=1);

namespace App\Controller\Grid\Gridscope;

use App\Entity\Gridscope;
use App\Form\Grid\GridscopeFormType;
use App\Repository\GridscopeRepository;
use App\Trait\FlashMessageTrait;
use App\Trait\FormStringValueTrait;
use App\Trait\GridscopeKeyTrait;
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
#[Route('/grid/scope/create', name: 'app_grid_scope_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class GridscopeCreateController
{
    /*
     * A GridScope is a bracket to which rules for columns can be linked.
     * This means that a value can be valid within one GridScope but not within another,
     * even though it belongs to the same column.
     */

    use FlashMessageTrait;
    use FormStringValueTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $urlGenerator,
        private GridscopeRepository $scopeRepository,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $scope = new Gridscope();
        $form = $this->formFactory->create(GridscopeFormType::class, $scope);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($scope);
            $this->entityManager->flush();
            $this->addFlash($request, 'success', 'flash.success.create');

            return new RedirectResponse($this->urlGenerator->generate('app_grid_scope_create'));
        }

        $scopes = $this->scopeRepository->findAll();

        return new Response($this->twig->render('grid/scope/gridscope_create.html.twig', [
            'form_scope' => $form->createView(),
            'scopes' => $scopes,
        ]));
    }
}
