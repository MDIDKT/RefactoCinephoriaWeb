<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\AvisRepository;
use App\Entity\Film;
use App\Entity\Avis;

final readonly class AvisService
{
    public function __construct(private AvisRepository $avisRepository)
    {
        // Initialisation du service avec le repository d'avis
    }

    // Récupération de tous les avis
    public function findAllAvis(): array
    {
        return $this->avisRepository->findAllAvis();
    }

    //recuperation des avis par film et utilisateur
    public function findAvisByFilmAndUser(Film $film, int $userId): ?Avis
    {
        return $this->avisRepository->findAvisByFilmAndUser($film->getId(), $userId);
    }

    // Ajout d'un avis
    public function addAvis(Film $film, User $userId, string $content, int $note): Avis
    {
        $avis = new Avis();
        $avis->setFilm($film);
        $avis->setUser($userId);
        $avis->setCommentaire($content);
        $avis->setNote($note);

        // Enregistrement de l'avis via le repository
        $this->avisRepository->save($avis);

        return $avis;
    }
}
