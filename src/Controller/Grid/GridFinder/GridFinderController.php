<?php

declare(strict_types=1);

namespace App\Controller\Grid\GridFinder;

use App\Enum\UserSettingEnum;
use App\Service\Grid\GridFinder\FindGridcells;
use App\Service\Grid\GridFinder\FindGridtables;
use App\Service\Grid\GridFinder\GetFinderFilter;
use App\Service\UserSetting\SetUserSetting;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;
use Webmozart\Assert\Assert;

#[AsController]
#[IsGranted('ROLE_GRIDADMIN')]
#[Route('/grid/finder', name: 'app_grid_finder', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class GridFinderController
{
    public function __construct(
        private GetFinderFilter $getFinderFilter,
        private SetUserSetting $setUserSetting,
        private FindGridtables $findGridtables,
        private FindGridcells $findGridcells,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $filter = $this->getFinderFilter->getFilter();

        if ($request->isMethod(Request::METHOD_POST)) {
            $filter = $request->get('finder_filter');
            Assert::string($filter);
            $this->setUserSetting->setSetting(UserSettingEnum::GRIDFINDER_FILTER, $filter);
        }

        // TODO 2024-11-29 ME: gefundene Tables/Cells in Session speichern (und abrufen, wenn filter unverändert)
        $tablesFound = $this->findGridtables->findTables($filter);
        $cellsFound = $this->findGridcells->findCells($filter);

        return new Response($this->twig->render('grid/finder/gridfinder.html.twig', [
            'finder_filter' => $filter,
            'finder_result_tables' => $tablesFound,
            'finder_result_cells' => $cellsFound,
        ]));
    }
}
