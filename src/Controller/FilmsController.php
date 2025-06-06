<?php

namespace App\Controller;

use App\Entity\Films;
use App\Form\FilmsType;
use App\Repository\FilmsRepository;
use App\Service\FilmService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/films')]
final class FilmsController extends AbstractController
{
    #[Route(name: 'app_films_index', methods: ['GET'])]
    public function index(FilmsRepository $filmsRepository): Response
    {
        // Récupère tous les films depuis le dépôt et les transmet à la vue 'films/index.html.twig'
        return $this->render('films/filmIndex.html.twig', [
            'films' => $filmsRepository->findAll(),
        ]);
    }
    #[Route('/{id}', name: 'app_films_show', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager, int $id): Response
    {
        // Récupère le film par son ID depuis le repository
        $film = $entityManager->getRepository(Films::class)->find($id);

        // Si aucun film n'est trouvé, renvoie une exception 404
        if (!$film) {
            throw $this->createNotFoundException('Le film avec cet identifiant n\'existe pas.');
        }

        // Récupère tous les avis liés au film et ne garde que ceux approuvés
        $avisApprouves = array_filter($film->getAvis()->toArray(), function ($avis) {
            return $avis->isApprouve();
        });

        // Rend la vue 'films/show.html.twig' en fournissant l'entité et la liste filtrée
        return $this->render('films/filmShow.html.twig', [
            'film' => $film,
            'avis' => $avisApprouves,
        ]);
    }
}
