<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Service\ReservationService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

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
     *
     * @param Request $request
     * @param ReservationService $reservationService
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        ReservationService $reservationService,
        EntityManagerInterface $entityManager
    ): Response {
        $reservation = new Reservation();
        $reservation->setUser($this->getUser());
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Ajou de la reservation
                $reservation->setUser($this->getUser());
                $entityManager->persist($reservation);
                $entityManager->flush();

                $this->addFlash('success', 'Réservation créée avec succès.');
                return $this->redirectToRoute('app_reservation_confirmation', ['id' => $reservation->getId()]);
            } catch (Exception $e) {
                $this->addFlash('danger', 'Erreur lors de la création de la réservation ' . $e->getMessage());
            } catch (Throwable) {
            }
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Affiche la confirmation d'une réservation.
     *
     * @param Reservation $reservation
     * @return Response
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
     *
     * @param Request $request
     * @param Reservation $reservation
     * @param ReservationService $reservationService
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
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
     *
     * @param Reservation $reservation
     * @return Response
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
