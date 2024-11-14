<?php

namespace App\Service\Coco;

use App\Repository\CocoItemRepository;

final readonly class CrudCocoItemInputs
{

    public function __construct(
        private CocoItemRepository $cocoItemRepository,
    )
    {
    }

    /**
     * @return array{'create':int, 'update':int, 'delete':int}
     */
    public function crudByInputs(array $inputs): array
    {
        $counts = ['create' => 0, 'update' => 0, 'delete' => 0];
        if (!isset($inputs['item'])) {
            return $counts;
        }

        $existingItems = $this->cocoItemRepository->findAll();


        return $counts;
    }
}