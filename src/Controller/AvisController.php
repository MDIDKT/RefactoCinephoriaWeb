<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/avis')]
final class AvisController extends AbstractController
{
    #[Route(name: 'app_avis_index', methods: ['GET'])]
    public function index (AvisRepository $avisRepository): Response
    {
        return $this->render ('avis/index.html.twig', [
            'avis' => $avisRepository->findAll (),
        ]);
    }

    #[Route('/new', name: 'app_avis_new', methods: ['GET', 'POST'])]
    public function new (Request $request, EntityManagerInterface $entityManager): Response
    {
        $avis = new Avis();
        $form = $this->createForm (AvisType::class, $avis);
        $form->handleRequest ($request);

        if ($form->isSubmitted () && $form->isValid ()) {
            $entityManager->persist ($avis);
            $entityManager->flush ();

            return $this->redirectToRoute ('app_avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render ('avis/new.html.twig', [
            'avis' => $avis,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_avis_show', methods: ['GET'])]
    public function show (Avis $avis): Response
    {
        return $this->render ('avis/show.html.twig', [
            'avis' => $avis,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_avis_edit', methods: ['GET', 'POST'])]
    public function edit (Request $request, Avis $avis, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm (AvisType::class, $avis);
        $form->handleRequest ($request);

        if ($form->isSubmitted () && $form->isValid ()) {
            $entityManager->flush ();

            return $this->redirectToRoute ('app_avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render ('avis/edit.html.twig', [
            'avis' => $avis,
            'form' => $form,
            'film' => $avis->getFilm (),
        ]);
    }

    #[Route('/{id}', name: 'app_avis_delete', methods: ['POST'])]
    public function delete (Request $request, Avis $avis, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid ('delete' . $avis->getId (), $request->getPayload ()->getString ('_token'))) {
            $entityManager->remove ($avis);
            $entityManager->flush ();
        }

        return $this->redirectToRoute ('app_avis_index', [], Response::HTTP_SEE_OTHER);
    }

}
