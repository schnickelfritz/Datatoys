<?php

namespace App\Controller\Grid\Gridsetting;

use App\Entity\Gridcol;
use App\Entity\Gridscope;
use App\Enum\UserSettingEnum;
use App\Form\Grid\GridsettingFormType;
use App\Service\Grid\CreateGridsettings;
use App\Service\Grid\GetFilteredGridcols;
use App\Service\Grid\GetSettingsCols;
use App\Service\UserSetting\SetUserSetting;
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
#[Route('/grid/setting/create/{id}', name: 'app_grid_setting_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class GridsettingCreateController
{
    use FlashMessageTrait;

    public function __construct(
        private GetFilteredGridcols $filteredGridcols,
        private FormFactoryInterface $formFactory,
        private CreateGridsettings $createGridsettings,
        private GetSettingsCols $getSettingsCols,
        private UrlGeneratorInterface $urlGenerator,
        private SetUserSetting $setUserSetting,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request, Gridscope $scope): Response
    {
        $this->setUserSetting->setSetting(UserSettingEnum::GRIDSCOPE_ID, $scope->getId());
        list($columns, $filter) = $this->filteredGridcols->getColsAndFilter($scope);

        $columnsChoices = array_combine(array_map(fn(Gridcol $col) => $col->getName(), $columns), $columns);
        $form = $this->formFactory->create(type:GridsettingFormType::class, options:['columns' => $columnsChoices]);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $settings = $this->getSettingsCols->getSettingsCols($scope, $columns);

            return new Response(
                $this->twig->render('grid/setting/gridsetting_create.html.twig', [
                    'form_setting' => $form->createView(),
                    'settings' => $settings,
                    'columns' => $columns,
                    'columns_filter' => $filter,
                    'selected_scope' => $scope,
                ])
            );
        }

        $errorMessage = $this->createGridsettings->createSettings(
            $scope,
            $form->get('columns')->getData(),
            $form->get('type')->getData(),
            $form->get('parameter')->getData()
        );
        if ($errorMessage !== null) {
            $this->addFlash($request, 'fail', $errorMessage);
        } else {
            $this->addFlash($request, 'success', 'flash.success.create');
        }


        return new RedirectResponse($this->urlGenerator->generate('app_grid_setting_create', ['id' => $scope->getId()]));
    }
}