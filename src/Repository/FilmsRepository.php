<?php

namespace App\Repository;

use App\Entity\Films;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Films>
 */
class FilmsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Films::class);
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('f')
            ->getQuery()
            ->getResult();
    }

    public function find3LastFilms()
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.id', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }
}
