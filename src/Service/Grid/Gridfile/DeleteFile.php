<?php

namespace App\Service\Grid\Gridfile;

use App\Entity\Gridfile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;

final readonly class DeleteFile
{

    public function __construct(
        #[Autowire('%app.gridfiles_folder_path%')]
        private string $gridfilesFolderPath,
    )
    {
    }

    public function deleteFile(string $storename): void
    {
        $filesystem = new Filesystem();
        $filepath = sprintf('%s/%s', $this->gridfilesFolderPath, $storename);
        if ($filesystem->exists($filepath)) {
            $filesystem->remove($filepath);
        }
    }
}