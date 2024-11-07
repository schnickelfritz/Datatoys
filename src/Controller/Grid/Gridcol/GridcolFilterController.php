<?php

namespace App\Controller\Grid\Gridcol;

use App\Enum\UserSettingEnum;
use App\Service\UserSetting\SetUserSetting;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Webmozart\Assert\Assert;

#[AsController]
#[IsGranted('ROLE_GRIDADMIN')]
#[Route('/grid/col/filter', name: 'app_grid_col_filter', methods: [Request::METHOD_POST])]
final readonly class GridcolFilterController
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private SetUserSetting $setUserSetting,
    )
    {
    }

    public function __invoke(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $nofilter = $request->get('nofilter');
            if ($nofilter !== null) {
                $this->setUserSetting->setSetting(UserSettingEnum::GRIDCOL_FILTER, '');
            } else {
                $filter = $request->get('columns_filter');
                Assert::string($filter);
                $this->setUserSetting->setSetting(UserSettingEnum::GRIDCOL_FILTER, $filter);
            }
        }

        $referer = $request->headers->get('referer');
        if ($referer !== null) {
            return new RedirectResponse($referer);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_grid_col_create'));
    }
}