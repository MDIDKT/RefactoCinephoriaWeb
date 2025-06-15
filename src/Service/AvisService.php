<?php

namespace App\Service;

use App\Repository\AvisRepository;
use App\Entity\Film;
use App\Entity\Avis;

class AvisService
{
    public function __construct(private readonly AvisRepository $avisRepository) {
        // Initialisation du service avec le repository d'avis
    }

    public function noterFilm(Film $film, int $note, string $commentaire, int $userID): Avis
    {
        // Création d'un avis en attente de validation
        $avis = new Avis();
        $avis->setFilm($film);
        $avis->setNote($note);
        $avis->setCommentaire($commentaire);
        $avis->setUser($userID);
    }

    public function validerAvis(Avis $avis): Avis
    {
        // Validation/modération d'un avis (employé/admin)
        $avis->setStatut(Avis::isVerified());
        $this->avisRepository->save($avis);
        return $avis;
    }

    public function getMoyenneFilm(Film $film): float
    {
        // Calcule la note moyenne pour le film
        $avisValides = $this->avisRepository->findBy(['film' => $film, 'statut' => Avis::STATUT_VALIDE]);
        $totalNotes = 0;
        $count = count($avisValides);
        foreach ($avisValides as $avis) {
            $totalNotes += $avis->getNote();
        }
        return $count > 0 ? $totalNotes / $count : 0.0;
    }
}
