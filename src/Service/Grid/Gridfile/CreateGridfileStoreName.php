<?php

namespace App\Service\Grid\Gridfile;

use App\Entity\Gridtable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

final readonly class CreateGridfileStoreName
{

    public function __construct(
        private SluggerInterface $slugger,
    )
    {
    }

    public function createStoreName(UploadedFile $uploadedFile, Gridtable $table): string
    {
        $originalName = $uploadedFile->getClientOriginalName();
        $sluggify = $this->slugger->slug($originalName);

        return sprintf('%d__%s-%s.%s', $table->getId(), $sluggify, uniqid(), $uploadedFile->guessExtension());
    }
}