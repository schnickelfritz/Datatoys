<?php

namespace App\Controller\Grid\Gridsetting;

use App\Entity\Gridscope;
use App\Service\Grid\Gridsettings\ImportGridsettings;
use App\Trait\FlashMessageTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;
use Webmozart\Assert\Assert;

#[AsController]
#[IsGranted('ROLE_GRIDADMIN')]
#[Route('/grid/setting/import/{id}', name: 'app_grid_setting_import', methods: [Request::METHOD_POST])]

final readonly class GridsettingImportController
{
    use FlashMessageTrait;

    public function __construct(
        private ImportGridsettings $importGridsettings,
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(Request $request, Gridscope $scope): Response
    {
        $importdata = $request->get('importdata');
        Assert::string($importdata, 'string expected');

        $successfullyImportedLines = $this->importGridsettings->processImportdata($importdata, $scope);
        $this->addFlash($request, 'success', $this->translator->trans('flash.success.updates', ['number' => count($successfullyImportedLines)]));

        return new RedirectResponse($this->urlGenerator->generate('app_grid_setting_create', ['id' => $scope->getId()]));
    }

}