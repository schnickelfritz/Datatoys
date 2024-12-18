<?php

namespace App\Controller\Grid\Gridfile;

use App\Entity\Gridtable;
use App\Service\Grid\Gridfile\DeleteGridfiles;
use App\Service\Grid\Gridfile\RenameGridfiles;
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
#[Route('/grid/file/update/{id}', name: 'app_grid_file_update', methods: [Request::METHOD_POST])]
final readonly class GridfileUpdateController
{

    use FlashMessageTrait;

    public function __construct(
        private DeleteGridfiles $deleteGridfiles,
        private RenameGridfiles $renameGridfiles,
        private UrlGeneratorInterface $urlGenerator,
        private TranslatorInterface $translator,
    )
    {
    }

    public function __invoke(Request $request, Gridtable $table): Response
    {
        if ($request->get('rename') !== null) {
            $this->rename($request);
        }

        if ($request->get('delete') !== null) {
            $this->delete($request);
        }

        return new RedirectResponse(
            $this->urlGenerator->generate('app_grid_file_create', ['id' => $table->getId()])
        );
    }

    private function delete(Request $request): void
    {
        $selected = $request->get('file_checked');
        if ($selected === null) {
            $this->addFlash($request, 'info', 'flash.info.nothing_selected');

            return;
        }

        $numberOfDeletedFiles = $this->deleteGridfiles->deleteByFileIds(array_keys($selected));
        $this->addFlash($request, 'success', $this->translator->trans('grid.file.flash.deleted', ['number' => $numberOfDeletedFiles]));
    }

    private function rename(Request $request): void
    {
        $filenames = $request->get('file_name');
        $filenamePart = $request->request->getString('filename_part');
        $filenameReplacer = $request->request->getString('replace_part');
        $optionAppend = $request->request->getBoolean('option_append');
        $optionPrepend = $request->request->getBoolean('option_prepend');
        $optionReplace = $request->request->getBoolean('option_replace');

        $numberOfRenames = $this->renameGridfiles->rename(
            $filenames, $filenamePart, $filenameReplacer, $optionAppend, $optionPrepend, $optionReplace
        );

        if ($numberOfRenames === 0) {
            $this->addFlash($request, 'info', 'flash.info.no_changes_found');
        } else {
            $this->addFlash($request, 'success', $this->translator->trans('flash.success.updates', ['number' => $numberOfRenames]));
        }
    }
}