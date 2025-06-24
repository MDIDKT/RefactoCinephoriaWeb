<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Service\ReservationService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reservation')]
final class ReservationController extends AbstractController
{
    /**
     * Liste les réservations de l’utilisateur connecté.
     */
    #[Route(name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();
        $reservations = $reservationRepository->findBy(['user' => $user]);

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * Crée une nouvelle réservation.
     */
    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationService $reservationService): Response
    {
        $form = $this->createForm(ReservationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $reservation = $reservationService->creerReservation(
                    $data->getNombrePlace(),
                    $data->getDate(),
                    10.0,
                    $data->getSieges(),
                );
                $reservation->setUser($this->getUser());
                $this->addFlash('success', 'Réservation créée avec succès.');
                return $this->redirectToRoute('app_reservation_confirmation', ['id' => $reservation->getId()]);
            } catch (Exception $e) {
                $this->addFlash('danger', 'Erreur lors de la création de la réservation ' . $e->getMessage());
            }
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Affiche la confirmation d'une réservation.
     */
    #[Route('/{id}/confirmation', name: 'app_reservation_confirmation', methods: ['GET'])]
    public function confirmation(Reservation $reservation): Response
    {
        if ($reservation->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('reservation/confirmation.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * Annule une réservation.
     */
    #[Route('/{id}/annulation', name: 'app_reservation_annulation', methods: ['POST'])]
    public function annulation(
        Request $request,
        Reservation $reservation,
        ReservationService $reservationService
    ): Response {
        if ($reservation->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if (
            $this->isCsrfTokenValid(
                'annulation' . $reservation->getId(),
                $request->getPayload()->getString('_token')
            )
        ) {
            $reservationService->annulerReservation($reservation);
            $this->addFlash('success', 'Réservation annulée.');
        } else {
            $this->addFlash('danger', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_reservation_index');
    }

    /**
     * Affiche le détail d'une réservation.
     */
    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        if ($reservation->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }
}
