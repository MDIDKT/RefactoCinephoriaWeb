<?php

namespace App\Repository;

use App\Entity\Incident;
use App\Enum\IncidentStatus;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Incident>
 */
class IncidentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct();
    }

    // créer un nouvel incident
    public function creerIncident(Incident $incident): void
    {
        if ($incident->getDate() === null) {
            $incident->setDate(new DateTimeImmutable('now'));
        }
        $this->getEntityManager()->persist($incident);
        $this->getEntityManager()->flush();
    }

    // Liste tous les incidents d'une salle
    public function getIncidentsSalle($salle): array
    {
        return $this->findBy(['salle' => $salle]);
    }

    // Met à jour le statut
    public function changerStatutIncident(Incident $incident, string $statut): void
    {
        $incident->setStatus(IncidentStatus::from($statut));
        $this->getEntityManager()->flush();
    }
}
