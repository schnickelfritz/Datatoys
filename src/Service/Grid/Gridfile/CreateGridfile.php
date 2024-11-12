<?php

namespace App\Service\Grid\Gridfile;

use App\Entity\Gridfile;
use App\Entity\Gridtable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class CreateGridfile
{

    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function create(Gridtable $table, Gridfile $gridfile, UploadedFile $uploadedFile, string $storename): void
    {
        $originalName = $uploadedFile->getClientOriginalName();
        $filesize = $uploadedFile->getSize();
        $mimeType = $uploadedFile->getMimeType();
        $type = $mimeType == null ? '' : $mimeType;

        $gridfile
            ->setOriginalName($originalName)
            ->setStoredName($storename)
            ->setType($type)
            ->setFilesize($filesize)
            ->setGridtable($table)
        ;

        if (str_starts_with($type, 'image/')) {
            $size = getimagesize($uploadedFile->getRealPath());
            if ($size !== false) {
                $gridfile->setWidth($size[0])->setHeight($size[1]);
            }
        }

        $this->entityManager->persist($gridfile);
        $this->entityManager->flush();
    }
}