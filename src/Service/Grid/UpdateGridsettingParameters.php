<?php

namespace App\Service\Grid;

use App\Repository\GridsettingRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UpdateGridsettingParameters
{
    public function __construct(
        private GridsettingRepository $gridsettingRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * @param array<int, string> $parameters
     */
    public function updateParameters(array $parameters): int
    {
        $countUpdates = 0;
        $settingIds = array_keys($parameters);
        $settings = $this->gridsettingRepository->findBy(['id' => $settingIds]);
        foreach ($settings as $setting) {
            $settingId = $setting->getId();
            if (!isset($parameters[$settingId])) {
                continue;
            }
            $parameter = $setting->getParameter();
            if ($parameter !== $parameters[$settingId]) {
                $setting->setParameter($parameters[$settingId]);
                $countUpdates++;
            }
        }
        $this->entityManager->flush();

        return $countUpdates;
    }
}