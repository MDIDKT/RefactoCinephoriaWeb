<?php

namespace App\Service;

use App\Repository\CinemaRepository;
use App\Repository\SalleRepository;

final readonly class CinemaService
{
    private CinemaRepository $cinemaRepository;
    private SalleRepository $salleRepository;

    public function __construct(CinemaRepository $cinemaRepository, SalleRepository $salleRepository)
    {
        $this->cinemaRepository = $cinemaRepository;
        $this->salleRepository = $salleRepository;
    }

    public function getCinemas()
    {
        // recupere tous les cinémas
        return $this->cinemaRepository->findAll();
    }

    public function getSallesByCinema($cinemaId)
    {
        // recupere les salles d'un cinéma
        return $this->salleRepository->findBy(['cinema' => $cinemaId]);
    }

}

