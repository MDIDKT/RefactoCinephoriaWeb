<?php

namespace App\Service;

use App\Repository\SeanceRepository;
use App\Entity\Film;
use App\Entity\Salle;
use App\Entity\Seance;

class SeanceService
{
    public function __construct(private readonly SeanceRepository $seanceRepository) {}

    public function verifierDisponibilite(Salle $salle, \DateTimeInterface $heureDebut, \DateTimeInterface $heureFin): bool
    {
        // Vérifie la disponibilité d'une salle sur un créneau donné
    }

    public function associerFilmSalle(Film $film, Salle $salle, array $data): Seance
    {
        // Associe un film à une salle pour créer une séance
    }

    public function creerSeance(array $data): Seance
    {
        // Crée une nouvelle séance
    }
}
