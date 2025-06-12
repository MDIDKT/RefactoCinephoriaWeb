<?php

namespace App\Controller;

use App\Entity\Cinema;
use App\Repository\CinemaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cinema')]
final class CinemaController extends AbstractController
{
    #[Route(name: 'app_cinema_index', methods: ['GET'])]
    public function index(CinemaRepository $cinemaRepository): Response
    {
        return $this->render('cinema/index.html.twig', [
            'cinemas' => $cinemaRepository->findAll(),
        ]);
    }
}
