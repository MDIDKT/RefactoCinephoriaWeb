<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Film;
use App\Form\AvisForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\AvisService;

#[Route('/avis')]
final class AvisController extends AbstractController
{
    #[Route(name: 'app_avis_index', methods: ['GET'])]
    public function index(AvisService $avisService): Response
    {
        $avis = $avisService->findAllAvis();
        return $this->render('avis/index.html.twig', [
            'avis' => $avis,
        ]);
    }

    #[Route('/new', name: 'app_avis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avis = new Avis();
        $form = $this->createForm(AvisForm::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setUser($this->getUser());
            $avis->setFilm($request->attributes->get('film'));
            $entityManager->persist($avis);
            $entityManager->flush();

            return $this->redirectToRoute('app_avis_index', [
                'id' => $avis->getId(),
            ]);
        }

        return $this->render('avis/new.html.twig', [
            'avis' => $avis,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_avis_show', methods: ['GET'])]
    public function show(Avis $avis): Response
    {
        return $this->render('avis/show.html.twig', [
            'avis' => $avis,
        ]);
    }
}
