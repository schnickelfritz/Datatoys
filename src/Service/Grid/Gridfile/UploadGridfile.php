<?php

namespace App\Service\Grid\Gridfile;

use App\Entity\Gridfile;
use App\Entity\Gridtable;
use App\Repository\GridfileRepository;
use App\Repository\GridtableRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UploadGridfile
{

    public function __construct(
        private GridtableRepository $gridtableRepository,
        private GridfileRepository      $gridfileRepository,
        private CreateGridfileStoreName $gridfileStoreName,
        private CreateGridfile          $createGridfile,
        private DeleteFile              $deleteFile,
        #[Autowire('%app.gridfiles_folder_path%')]
        private string                  $gridfilesFolderPath,
    )
    {
    }

    public function upload(UploadedFile $uploadedFile, int $tableId, bool $allowReplace): ?string
    {
        $table = $this->gridtableRepository->find($tableId);
        if (!$table instanceof Gridtable) {
            return 'grid.file.error.table_not_found';
        }
        $originalName = $uploadedFile->getClientOriginalName();
        if (!$uploadedFile->isValid()) {
            return 'grid.file.error.file_invaild';
        }

        $gridfile = $this->gridfileRepository->findOneBy(['gridtable' => $table, 'originalName' => $originalName]);

        if ($gridfile instanceof Gridfile) {
            if ($allowReplace === false) {
                return 'grid.file.error.file_exists';
            }
            $this->deleteFile->deleteFile($gridfile->getStoredName());
        } else {
            $gridfile = new Gridfile();
        }

        $storeName = $this->gridfileStoreName->createStoreName($uploadedFile, $table);
        try {
            $this->createGridfile->create($table, $gridfile, $uploadedFile, $storeName);
            $uploadedFile->move($this->gridfilesFolderPath, $storeName);
            $message = null;
        } catch (FileException $fileException) {
            $message = 'grid.file.error.upload_fail';
        }

        return $message;
    }
}