<?php

namespace App\Repository;

use App\Entity\Seance;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Seance>
 */
class SeanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seance::class);
    }

    // recuperation de toutes les seances
    public function findAll(): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.heureDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // recuperation des seances par date
    public function findByDate(DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.heureDebut >= :date')
            ->setParameter('date', $date->setTime(0, 0, 0))
            ->orderBy('s.heureDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // recuperation des seances par film
    public function findByFilm(int $filmId): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.film = :filmId')
            ->setParameter('filmId', $filmId)
            ->orderBy('s.heureDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
