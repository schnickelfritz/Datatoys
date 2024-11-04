<?php

declare(strict_types=1);

namespace App\Controller\Grid\Gridcol;

use App\Entity\Gridcol;
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
#[Route('/grid/col/delete/{id}', name: 'app_grid_col_delete', methods: [Request::METHOD_POST])]
final readonly class GridcolDeleteController
{
    use FlashMessageTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $urlGenerator,
        private TranslatorInterface $translator,
    ) {
    }

    public function __invoke(Request $request, Gridcol $col): Response
    {
        $this->entityManager->remove($col);
        $this->entityManager->flush();
        $this->addFlash($request, 'success', $this->translator->trans('grid.col.delete.flash', ['name' => $col->getName()]));

        return new RedirectResponse($this->urlGenerator->generate('app_grid_col_create'));
    }
}
