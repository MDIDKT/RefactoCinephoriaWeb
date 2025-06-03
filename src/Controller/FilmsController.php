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
    public function __construct(private FilmService $filmService)
    {
        $this->filmService = $filmService;
    }
    /**
     * Affiche la liste de tous les films.
     *
     * @param FilmsRepository $filmsRepository
     * @return Response
     */
    #[Route(name: 'app_films_index', methods: ['GET'])]
    public function index(FilmsRepository $filmsRepository): Response
    {
        // Récupère tous les films depuis le dépôt et les transmet à la vue 'films/index.html.twig'
        return $this->render('films/index.html.twig', [
            'films' => $filmsRepository->findAll(),
        ]);
    }

    /**
     * Affiche le formulaire de création d'un nouveau film et traite sa soumission.
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/new', name: 'app_films_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {

        // Crée une nouvelle instance de l'entité Films
        $film = new Films();
        // Génére le formulaire à partir de la classe FilmsType
        $form = $this->createForm(FilmsType::class, $film);
        // Traite la requête HTTP (POST) pour hydrater l'objet $film
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide, on enregistre le film en base
        if ($form->isSubmitted() && $form->isValid()) {
           // this->filmService->creerFilm($film);

            // Redirige vers la route 'app_films_show'
            // (Note : telle qu’elle est, elle ne transmet pas l’ID dans l’URL)
            return $this->redirectToRoute('app_films_show', [], Response::HTTP_SEE_OTHER);
        }

        // Affiche le formulaire de création si non soumis ou en cas d’erreur de validation
        return $this->render('films/new.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
    }

    /**
     * Affiche les détails d'un film et ses avis approuvés.
     *
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @return Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
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
        return $this->render('films/show.html.twig', [
            'film' => $film,
            'avis' => $avisApprouves,
        ]);
    }

    /**
     * Affiche le formulaire d'édition d'un film existant et traite sa mise à jour.
     *
     * @param Request $request
     * @param Films $film   L’entité $film est injectée automatiquement grâce à ParamConverter
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/{id}/edit', name: 'app_films_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Films $film, EntityManagerInterface $entityManager): Response
    {
        // Crée le formulaire pré-rempli avec les données du film passé en paramètre
        $form = $this->createForm(FilmsType::class, $film);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide, on flush pour appliquer les changements
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Redirige vers la liste des films
            return $this->redirectToRoute('app_films_index', [], Response::HTTP_SEE_OTHER);
        }

        // Affiche le formulaire d'édition si non soumis ou en cas d’erreur
        return $this->render('films/edit.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
    }

    /**
     * Supprime un film existant.
     *
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    #[Route('/films/delete/{id}', name: 'app_films_delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        // Récupère le film à supprimer via son ID
        $film = $entityManager->getRepository(Films::class)->find($id);
        dump($id); // Permet d'afficher l'ID passé pour le debug

        // Si le film n'existe pas, on renvoie une exception 404
        if (!$film) {
            throw $this->createNotFoundException('Film introuvable');
        }

        // Supprime le film et exécute la requête SQL en base
        $entityManager->remove($film);
        $entityManager->flush();

        // Redirige vers la liste des films après suppression
        return $this->redirectToRoute('app_films_index');
    }
}
