<?php

declare(strict_types=1);

namespace App\Controller\Grid;

use App\Entity\Gridscope;
use App\Trait\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsController]
#[IsGranted('ROLE_GRIDADMIN')]
#[Route('/grid/scope/delete/{id}', name: 'app_grid_scope_delete', methods: [Request::METHOD_POST])]
final readonly class GridscopeDeleteController
{
    use FlashMessageTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $urlGenerator,
        private TranslatorInterface $translator,
    ) {
    }

    public function __invoke(Request $request, Gridscope $scope): Response
    {
        $this->entityManager->remove($scope);
        $this->entityManager->flush();
        $this->addFlash($request, 'success', $this->translator->trans('grid.scope.delete.flash', ['name' => $scope->getName()]));

        return new RedirectResponse($this->urlGenerator->generate('app_grid_scope_create'));
    }
}
