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
    public function __construct (ManagerRegistry $registry)
    {
        parent::__construct ($registry, Films::class);
    }


    public function findAll (): array
    {
        return $this->createQueryBuilder ('f')
            ->getQuery ()
            ->getResult ();
    }



    //    /**
    //     * @return Films[] Returns an array of Films objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Films
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function find3LastFilms ()
    {
        return $this->createQueryBuilder ('f')
            ->orderBy ('f.id', 'DESC')
            ->setMaxResults (3)
            ->getQuery ()
            ->getResult ();
    }
}
