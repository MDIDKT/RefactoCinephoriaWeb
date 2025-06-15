<?php

namespace App\Service;

use App\Repository\IncidentRepository;
use App\Entity\Salle;
use App\Entity\Employe;
use App\Entity\Incident;

class IncidentService
{
    public function __construct(private readonly IncidentRepository $incidentRepository) {}

    public function creerIncident(Salle $salle, Employe $employe, string $description): Incident
    {
        // Enregistre un nouvel incident
    }

    public function getIncidentsSalle(Salle $salle): array
    {
        // Liste tous les incidents d'une salle
    }

    public function changerStatutIncident(Incident $incident, string $statut): Incident
    {
        // Met à jour le statut (ex : "en_cours" → "résolu")
    }
}
