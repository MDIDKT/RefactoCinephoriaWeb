<?php

namespace App\Service;

use App\Repository\SeanceRepository;
use DateTimeInterface;

readonly class SeanceService
{
    private SeanceRepository $seanceRepository;

    public function __construct(SeanceRepository $seanceRepository)
    {
        $this->seanceRepository = $seanceRepository;
    }

    // recuperation de toutes les seances
    public function getAllSeances(): array
    {
        return $this->seanceRepository->findAll();
    }

    // recuperation des seances par date
    public function getSeancesByDate(DateTimeInterface $date): array
    {
        return $this->seanceRepository->findByDate($date);
    }

    // recuperation des seances par film
    public function getSeancesByFilm(int $filmId): array
    {
        return $this->seanceRepository->findByFilm($filmId);
    }
}
