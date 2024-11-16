<?php

namespace App\Repository;

use App\Entity\Gridfile;
use App\Entity\Gridtable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gridfile>
 */
class GridfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gridfile::class);
    }

    /**
     * @return array<string, Gridfile>
     */
    public function existingFilesByName(Gridtable $table): array
    {
        $files = [];
        foreach ($this->findBy(['gridtable' => $table]) as $gridfile) {
            $files[strtolower($gridfile->getOriginalName())] = $gridfile;
        }

        return $files;
    }

}
