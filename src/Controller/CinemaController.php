<?php

namespace App\Controller;

use App\Service\CinemaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cinema')]
final class CinemaController extends AbstractController
{
    #[Route(name: 'app_cinema_index', methods: ['GET'])]
    public function index(CinemaService $cinemaService): Response
    {
        return $this->render('cinema/index.html.twig', [
            'cinemas' => $cinemaService->getCinemas(),
        ]);
    }

    #[Route('/{cinemaId}', name: 'app_cinema_show', methods: ['GET'])]
    public function show($cinemaId, CinemaService $cinemaService): Response
    {
        $salles = $cinemaService->getSallesByCinema($cinemaId);

        return $this->render('cinema/show.html.twig', [
            'salles' => $salles,
        ]);
    }
}
