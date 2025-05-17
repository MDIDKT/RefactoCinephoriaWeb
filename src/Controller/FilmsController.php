<?php

namespace App\Controller;

use App\Entity\Films;
use App\Form\FilmsType;
use App\Repository\FilmsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/films')]
final class FilmsController extends AbstractController
{
    #[Route(name: 'app_films_index', methods: ['GET'])]
    public function index (FilmsRepository $filmsRepository): Response
    {
        return $this->render ('films/index.html.twig', [
            'films' => $filmsRepository->findAll (),
        ]);
    }

    #[Route('/new', name: 'app_films_new', methods: ['GET', 'POST'])]
    public function new (Request $request, EntityManagerInterface $entityManager): Response
    {
        $film = new Films();
        $form = $this->createForm (FilmsType::class, $film);
        $form->handleRequest ($request);

        if ($form->isSubmitted () && $form->isValid ()) {
            $entityManager->persist ($film);
            $entityManager->flush ();

            return $this->redirectToRoute ('app_films_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render ('films/new.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_films_show', methods: ['GET'])]
    public function show (EntityManagerInterface $entityManager, int $id): Response
    {
        $film = $entityManager->getRepository (Films::class)->find ($id);

        if (!$film) {
            throw $this->createNotFoundException ('Le film avec cet identifiant n\'existe pas.');
        }


        $avisApprouves = array_filter ($film->getAvis ()->toArray (), function($avis) {
            return $avis->isApprouve ();
        });

        return $this->render ('films/show.html.twig', [
            'film' => $film,
            'avis' => $avisApprouves
        ]);
    }

    #[Route('/{id}/edit', name: 'app_films_edit', methods: ['GET', 'POST'])]
    public function edit (Request $request, Films $film, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm (FilmsType::class, $film);
        $form->handleRequest ($request);

        if ($form->isSubmitted () && $form->isValid ()) {
            $entityManager->flush ();

            return $this->redirectToRoute ('app_films_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render ('films/edit.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
    }

    #[Route('/films/delete/{id}', name: 'app_films_delete', methods: ['POST'])]
    public function delete (int $id, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le film via son ID
        $film = $entityManager->getRepository (Films::class)->find ($id);
        dump ($id);

        // Vérifier si le film existe
        if (!$film) {
            throw $this->createNotFoundException ('Film introuvable');
        }

        // Supprimer le film
        $entityManager->remove ($film);
        $entityManager->flush ();
        // Rediriger vers la liste des films
        return $this->redirectToRoute ('app_films_index');
    }
}
