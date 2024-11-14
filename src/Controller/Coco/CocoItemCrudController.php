<?php

namespace App\Controller\Coco;

use App\Service\Coco\CrudCocoItemInputs;
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
#[Route('/coco/item/crud', 'app_coco_item_crud', methods: [Request::METHOD_POST])]
#[IsGranted('ROLE_COCO_ADMIN')]
final readonly class CocoItemCrudController
{
    use FlashMessageTrait;

    public function __construct(
        private CrudCocoItemInputs $crudCocoItemInputs,
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    public function __invoke(Request $request): Response
    {
        $inputs = $request->request->all();
        $counts = $this->crudCocoItemInputs->crudByInputs($inputs);
        $this->flash($request, $counts);

        return new RedirectResponse($this->urlGenerator->generate('app_coco_item_list'));
    }

    /**
     * @param array{'create':int, 'update':int, 'delete':int} $counts
     */
    private function flash(Request $request, array $counts): void
    {
        $sum = array_sum($counts);
        if ($sum <= 0) {
            $this->addFlash($request, 'info', 'flash.info.no_changes_found');
        } else {
            $this->addFlash($request, 'success', $this->translator->trans('coco.item.crud_flash', $counts));
        }
    }

}