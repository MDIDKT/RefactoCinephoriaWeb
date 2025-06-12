<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(FilmRepository $filmsRepository): Response
    {
        $films = $filmsRepository->findAll();

        return $this->render('home/index.html.twig', [
            'films' => $films,
            /*'filmsLast' => $filmsRepository->find3LastFilms(),*/
        ]);
    }
}
