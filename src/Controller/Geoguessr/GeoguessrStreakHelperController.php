<?php

namespace App\Controller\Geoguessr;

use App\Service\Geoguessr\GetGeoguessrData;
use App\Service\Geoguessr\SetGeoguessrData;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

#[AsController]
#[IsGranted('ROLE_GEOGUESSR')]
#[Route('/geoguessr', name: 'app_geoguessr', methods: [Request::METHOD_GET, Request::METHOD_POST])]

final readonly class GeoguessrStreakHelperController
{

    public function __construct(
        private GetGeoguessrData $getGeoguessrData,
        private SetGeoguessrData $setGeoguessrData,
        private UrlGeneratorInterface $urlGenerator,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $isReady = $this->getGeoguessrData->isReady();
        if (!$request->isMethod(Request::METHOD_POST)) {
            return new Response($this->twig->render('geoguessr/streakhelper.html.twig', [
                'ready' => $isReady,
                'wildcard' => $this->getGeoguessrData->getWildcard(),
                'colnames' => $this->getGeoguessrData->getAllColnames(),
                'all_values_by_cols' => $this->getGeoguessrData->getAllValuesByCols(),
                'set_values' => $this->getGeoguessrData->getSetValues(),
                'hits' => $this->getGeoguessrData->getHits(),
            ]));
        }

        if ($isReady) {
            $this->setGeoguessrData->setFromRowValues($request->get('rowvalue'));
            $wildcard = $this->setGeoguessrData->spreadWildcard($request->get('wildcard'));
            $this->setGeoguessrData->setWildcard($wildcard);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_geoguessr'));
    }

}