<?php

namespace App\Service;

use App\Entity\Films;
Use App\Repository\FilmsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class FilmService
{
    private EntityManagerInterface $entityManager;
    private FilmsRepository $filmsRepository;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        FilmsRepository        $filmsRepository,
        LoggerInterface        $logger
    )
    {
        $this->entityManager = $entityManager;
        $this->filmsRepository = $filmsRepository;
        $this->logger = $logger;
    }

    public function creerFilm(Films $film): void
    {
        $this->entityManager->persist($film);
        $this->entityManager->flush();
    }

    public function mettreAJourFilm(Films $film): void
    {
        $this->entityManager->flush();
    }

    public function supprimerFilm(Films $film): void
    {
        $this->entityManager->remove($film);
        $this->entityManager->flush();
        $this->logger->info('Film supprimé : ' . $film->getTitre());
    }

    public function getAvisApprouves(Films $film): array
    {
        // Récupère les avis approuvés du film
        return $film->getAvis()->filter(function ($avis) {
            return $avis->isApprouve();
        })->toArray();
    }

}