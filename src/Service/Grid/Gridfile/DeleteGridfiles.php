<?php

namespace App\Service\Grid\Gridfile;

use App\Repository\GridfileRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DeleteGridfiles
{

    public function __construct(
        private DeleteFile $deleteFile,
        private GridfileRepository $gridfileRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * @param int[] $gridfileIds
     */
    public function deleteByFileIds(array $gridfileIds): int
    {
        $gridfiles = $this->gridfileRepository->findBy(['id' => $gridfileIds]);
        foreach ($gridfiles as $gridfile) {
            $storename = $gridfile->getStoredName();
            $this->deleteFile->deleteFile($storename);
            $this->entityManager->remove($gridfile);

        }
        $this->entityManager->flush();

        return count($gridfiles);
    }
}