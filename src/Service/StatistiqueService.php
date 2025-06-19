<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
// (Possiblement dépendance MongoDB)

class StatistiqueService
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function getReservationsParFilm(int $filmId, \DateTimeInterface $dateDebut, \DateTimeInterface $dateFin): int
    {
        // Retourne le nombre de réservations pour un film sur une période
    }

    public function getTopFilms(int $limit = 5): array
    {
        // Liste les films les plus réservés
    }

    public function getTauxOccupation(Salle $salle): float
    {
        // Taux d’occupation d'une salle sur une période
    }
}
