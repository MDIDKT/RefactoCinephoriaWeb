<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @property mixed $_em
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function save(Reservation $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Reservation $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findAllReservations()
    {
        return $this->createQueryBuilder('r')
            ->getQuery()
            ->getResult();
    }

    public function findByFilmAndCinema($film, $cinema)
    {
        return $this->createQueryBuilder('r')
            ->where('r.film = :film')
            ->andWhere('r.cinema = :cinema')
            ->setParameter('film', $film)
            ->setParameter('cinema', $cinema)
            ->getQuery()
            ->getResult();
    }

    public function findBySeance($seance)
    {
        return $this->createQueryBuilder('r')
            ->where('r.seance = :seance')
            ->setParameter('seance', $seance)
            ->getQuery()
            ->getResult();
    }

    public function findByStatus($status)
    {
        return $this->createQueryBuilder('r')
            ->where('r.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult();
    }
}
