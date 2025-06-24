<?php

namespace App\Repository;

use App\Entity\Reservation;
use DateTimeInterface;
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

    /**
     * Retourne toutes les réservations pour un utilisateur donné, trié par date de création.
     *
     * @param int $userId
     * @return Reservation[]
     */
    public function findByUser(int $userId): array
    {
        return $this->findBy(['user' => $userId], ['createdAt' => 'DESC']);
    }

    /**
     * Retourne toutes les réservations pour une séance donnée, triée par date de création.
     *
     * @param int $seanceId
     * @return Reservation[]
     */
    public function findBySeance(int $seanceId): array
    {
        return $this->findBy(['seance' => $seanceId], ['createdAt' => 'DESC']);
    }

    /**
     * Vérifie la disponibilité des sièges pour une séance.
     */
    public function checkDisponibiliteSieges(int $seanceId, int $nombreDemandes, int $nombreTotalSieges): bool
    {
        $total = $this->createQueryBuilder('r')
            ->select('SUM(r.nombrePlace)')
            ->where('r.seance = :seanceId')
            ->setParameter('seanceId', $seanceId)
            ->getQuery()
            ->getSingleScalarResult();

        $siegesReserves = $total ? (int)$total : 0;
        return ($nombreTotalSieges - $siegesReserves) >= $nombreDemandes;
    }

    /**
     * @param DateTimeInterface $dateDebut
     * @param DateTimeInterface $dateFin
     * @return int
     * Retourne le nombre total de réservations créées entre deux dates.
     */
    public function countReservationsBetweenDates(DateTimeInterface $dateDebut, DateTimeInterface $dateFin): int
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.createdAt BETWEEN :debut AND :fin')
            ->setParameter('debut', $dateDebut)
            ->setParameter('fin', $dateFin)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
