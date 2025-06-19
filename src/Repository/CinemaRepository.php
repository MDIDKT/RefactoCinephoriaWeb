<?php

namespace App\Repository;

use App\Entity\Cinema;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cinema>
 */
class CinemaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cinema::class);
    }

    //recupere tous les cinémas
    public function findAllCinemas(): array
    {
        return $this->findAll();
    }

    // recupere les salles d'un cinéma
    public function findSallesByCinema($cinemaId): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.salles', 's')
            ->addSelect('s')
            ->where('c.id = :cinemaId')
            ->setParameter('cinemaId', $cinemaId)
            ->getQuery()
            ->getResult();
    }
}
