<?php

namespace App\Controller\Api;

use App\Repository\FilmsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiFilmsController extends AbstractController
{
    #[Route('/api/films', name: 'api_get_films', methods: ['GET'])]
    public function getFilms(FilmsRepository $filmRepository): JsonResponse
    {
        $films = $filmRepository->findAll();

        return $this->json($films, 200, [], ['groups' => 'film:read']);
    }
}
