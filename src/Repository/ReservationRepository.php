<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Service\ReservationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    //Faire une nouvelle reservation
    public function save(ReservationService $reservationService): void
    {
        $this->_em->persist($reservationService);
        $this->_em->flush();
    }
}
