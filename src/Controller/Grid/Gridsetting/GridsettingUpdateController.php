<?php

declare(strict_types=1);

namespace App\Controller\Grid\Gridsetting;

use App\Entity\Gridscope;
use App\Service\Grid\Gridsettings\UpdateGridsettingParameters;
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
#[Route('/grid/setting/update/{id}', name: 'app_grid_setting_update', methods: [Request::METHOD_POST])]
final readonly class GridsettingUpdateController
{
    use FlashMessageTrait;

    public function __construct(
        private UpdateGridsettingParameters $updateGridsettingParameters,
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(Request $request, Gridscope $scope): Response
    {
        $parameters = $request->get('setting_parameter');
        Assert::isArray($parameters, 'array expected');

        $countUpdates = $this->updateGridsettingParameters->updateParameters($parameters);
        $this->addFlash($request, 'success', $this->translator->trans('flash.success.updates', ['number' => $countUpdates]));

        return new RedirectResponse($this->urlGenerator->generate('app_grid_setting_create', ['id' => $scope->getId()]));
    }
}
