<?php

namespace App\Controller\Grid\Gridfile;

use App\Entity\Gridtable;
use App\Service\Grid\Gridfile\LinkGridfilesToTableCells;
use App\Trait\FlashMessageTrait;
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
#[Route('/grid/file/link-to-content/{id}', name: 'app_grid_file_links', methods: [Request::METHOD_POST])]
final readonly class GridfileLinkToCellsController
{
    use FlashMessageTrait;

    public function __construct(
        private LinkGridfilesToTableCells $linkGridfilesToTableCells,
        private UrlGeneratorInterface $urlGenerator,
        private TranslatorInterface $translator,
    )
    {
    }

    public function __invoke(Request $request, Gridtable $table): Response
    {
        $filenamesLinked = $this->linkGridfilesToTableCells->linkToCells($table);
        if (count($filenamesLinked) === 0) {
            $this->addFlash($request, 'info', 'grid.file.flash.no_links');
        } else {
            $this->addFlash(
                $request,
                'success',
                $this->translator->trans('grid.file.flash.links', ['number' => count($filenamesLinked)])
            );
        }

        return new RedirectResponse(
            $this->urlGenerator->generate('app_grid_file_create', ['id' => $table->getId()])
        );
    }
}