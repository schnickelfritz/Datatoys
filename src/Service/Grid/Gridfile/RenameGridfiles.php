<?php

namespace App\Service\Grid\Gridfile;

use App\Repository\GridfileRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class RenameGridfiles
{

    public function __construct(
        private GridfileRepository $gridfileRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function rename(
        array $filenamesById,
        string $filenamePart,
        string $replacer,
        bool $optionAppend,
        bool $optionPrepend,
        bool $optionReplace
    ): int
    {
        $countRenames = 0;
        $fileIds = array_keys($filenamesById);
        $gridfiles = $this->gridfileRepository->findBy(['id' => $fileIds]);
        foreach ($gridfiles as $gridfile) {
            $fileId = $gridfile->getId();
            if (!isset($filenamesById[$fileId])) {
                continue;
            }
            $filenameNew = $this->nameUpdate(
                $filenamesById[$fileId], $filenamePart, $replacer, $optionAppend, $optionPrepend, $optionReplace
            );
            if ($filenameNew !== '' && $filenameNew != $gridfile->getOriginalName()) {
                $gridfile->setOriginalName($filenameNew);
                $this->entityManager->persist($gridfile);
                $countRenames++;
            }
            $this->entityManager->flush();
        }

        return $countRenames;
    }

    private function nameUpdate(
        string $filename,
        string $filenamePart,
        string $replacer,
        bool $optionAppend,
        bool $optionPrepend,
        bool $optionReplace
    ): string
    {
        if ($filenamePart === '') {
            return $filename;
        }
        if ($optionAppend) {
            $filename = $filenamePart . $filename;
        }
        if ($optionPrepend) {
            $filename = $filename . $filenamePart;
        }
        if ($optionReplace) {
            $filename = str_replace($filenamePart, $replacer, $filename);
        }
        return $filename;
    }
}