<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Service\SeanceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/seance')]
final class SeanceController extends AbstractController
{
    #[Route(name: 'app_seance_index', methods: ['GET'])]
    public function index(SeanceService $seanceService): Response
    {
        $seances = $seanceService->getAllSeances();

        return $this->render('seance/index.html.twig', [
            'seances' => $seances,
        ]);
    }

    #[Route('/{id}', name: 'app_seance_show', methods: ['GET'])]
    public function show(SeanceService $seanceService, Seance $seance): Response
    {
        $seances = $seanceService->getSeancesByFilm($seance->getFilm()->getId());
        $film = $seance->getFilm();
        return $this->render('seance/show.html.twig', [
            'seance' => $seance,
            'seances' => $seances,
            'film' => $film,
        ]);
    }
}
