<?php

namespace App\Controller;

use App\Service\FilmService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/film')]
final class FilmController extends AbstractController
{
    #[Route(name: 'app_film_index', methods: ['GET'])]
    public function index(FilmService $filmService): Response
    {
        return $this->render('film/index.html.twig', [
            'films' => $filmService->getAllFilms(),
        ]);
    }

    #[Route('/{id}', name: 'app_film_show', methods: ['GET'])]
    public function show(int $id, FilmService $filmService): Response
    {
        $film = $filmService->getFilmById($id);
        if (!$film) {
            throw $this->createNotFoundException('Pas de film trouvé avec cet identifiant.');
        }
        return $this->render('film/show.html.twig', [
            'film' => $film,
        ]);
    }

}
