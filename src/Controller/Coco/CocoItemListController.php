<?php

namespace App\Controller\Coco;

use App\Repository\CocoItemRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

#[AsController]
#[Route('/coco/item/list', 'app_coco_item_list', methods: [Request::METHOD_GET])]
#[IsGranted('ROLE_COCO_ADMIN')]
final readonly class CocoItemListController
{

    public function __construct(
        private CocoItemRepository $cocoItemRepository,
        private Environment $twig,
    )
    {
    }

    public function __invoke(Request $request): Response
    {
        $cocoItems = $this->cocoItemRepository->findAll();
        return new Response($this->twig->render('coco/itemlist.html.twig', [
            'coco_items' => $cocoItems,
        ]));

    }
}