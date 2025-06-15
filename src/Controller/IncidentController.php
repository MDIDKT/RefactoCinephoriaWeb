<?php
// src/Controller/IncidentController.php

namespace App\Controller;

use App\Entity\Incident;
use App\Form\IncidentForm;
use App\Service\IncidentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/incident')]
final class IncidentController extends AbstractController
{
    #[Route('/', name: 'app_incident_index', methods: ['GET'])]
    public function index(Request $request, IncidentService $incidentService): Response
    {
        $salle = $request->query->get('salle');
        $statut = $request->query->get('statut');

        $incidents = $incidentService->listerIncidents($salle, $statut);

        return $this->render('incident/index.html.twig', [
            'incidents' => $incidents,
            'salle' => $salle,
            'statut' => $statut,
        ]);
    }

    #[Route('/new', name: 'app_incident_new', methods: ['GET', 'POST'])]
    public function new(Request $request, IncidentService $incidentService): Response
    {
        $incident = new Incident();
        $form = $this->createForm(IncidentForm::class, $incident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $incidentService->creerIncident($incident);
            return $this->redirectToRoute(route: 'app_incident_index');
        }

        return $this->render('incident/new.html.twig', [
            'incidents' => $incident,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/show/{id}', name: 'app_incident_show', methods: ['GET'])]
    public function show(Incident $incident): Response
    {
        return $this->render('incident/show.html.twig', [
            'incident' => $incident,
        ]);
    }

}
