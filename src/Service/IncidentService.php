<?php

namespace App\Service;

use App\Entity\Incident;
use App\Entity\Salle;
use App\Repository\IncidentRepository;

class IncidentService
{
    private IncidentRepository $incidentRepository;

    public function __construct(IncidentRepository $incidentRepository)
    {
        $this->incidentRepository = $incidentRepository;
    }

    public function listerIncidents(?Salle $salle = null, ?string $statut = null): array
    {
        if ($salle || $statut) {
            return $this->incidentRepository->findBy([
                'salle' => $salle,
                'status' => $statut,
            ]);
        }

        return $this->incidentRepository->findAll();
    }

    public function creerIncident(Incident $incident): void
    {
        $this->incidentRepository->creerIncident($incident);
    }
}
