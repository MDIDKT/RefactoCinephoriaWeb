<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Reservations;
use App\Form\ReservationsType;
use App\Repository\ReservationsRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reservations')]
#[IsGranted('ROLE_USER')] // Seuls les utilisateurs connectés peuvent accéder à ces routes et effectuer des réservations
final class ReservationsController extends AbstractController
{
    #[Route('/index', name: 'app_reservations_index', methods: ['GET'])]
    public function index(ReservationsRepository $reservationsRepository): Response
    {
        return $this->render('reservations/index.html.twig', [
            'reservations' => $reservationsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservations_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservations();
        $reservation->setDate(new DateTimeImmutable());
        $form = $this->createForm(ReservationsType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processReservation($reservation, $entityManager);
        }

        return $this->render('reservations/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    private function processReservation(Reservations $reservation, EntityManagerInterface $entityManager): Response
    {
        $seance = $reservation->getSeances();

        // Vérification de la validité de la séance et de la salle
        if ($seance === null || $seance->getSalle() === null) {
            $this->addFlash('error', 'Séance ou salle invalide pour cette réservation.');
            return $this->render('reservations/new.html.twig', [
                'reservation' => $reservation,
                'form' => $this->createForm(ReservationsType::class, $reservation)->createView(),
            ]);
        }

        $salle = $seance->getSalle();
        $requestedSeats = $reservation->getNombrePlaces();

        // 1. Validation du nombre de places demandé
        if ($requestedSeats <= 0) {
            $this->addFlash('error', 'Le nombre de places doit être supérieur à 0.');
            return $this->render('reservations/new.html.twig', [
                'reservation' => $reservation,
                'form' => $this->createForm(ReservationsType::class, $reservation)->createView(),
            ]);
        }

        // 2. Calcul des places disponibles
        $placesDisponibles = $salle->getNombreSiege() - $salle->getPlacesOccupees();

        if ($requestedSeats > $placesDisponibles) {
            $this->addFlash('error', sprintf(
                'Réservation impossible : vous demandez %d places, mais il n’en reste que %d disponibles.',
                $requestedSeats,
                $placesDisponibles
            ));
            return $this->render('reservations/new.html.twig', [
                'reservation' => $reservation,
                'form' => $this->createForm(ReservationsType::class, $reservation)->createView(),
            ]);
        }

        // 3. Réduction des places disponibles et sauvegarde
        if ($requestedSeats > $placesDisponibles) {
            $this->addFlash('error', sprintf(
                'Impossible de réserver %d places. Seulement %d places disponibles.',
                $requestedSeats,
                $placesDisponibles
            ));
            return $this->render('reservations/new.html.twig', [
                'reservation' => $reservation,
                'form' => $this->createForm(ReservationsType::class, $reservation)->createView(),
            ]);
        }
        $salle->reservePlaces($requestedSeats);

        // 4. Calculer le prix total
        $reservation->setPrixTotal($reservation->calculprixTotal($seance));

        $entityManager->persist($salle);
        $entityManager->persist($reservation);
        $entityManager->flush();

        $this->addFlash('success', 'Réservation effectuée avec succès.');
        return $this->redirectToRoute('app_reservations_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_reservations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservations $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationsType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $initialReservedSeats = $reservation->getNombrePlaces(); // Places réservées avant modification
            $salle = $reservation->getSeances()->getSalle();

            if ($salle !== null) {
                // Vérifier si le nombre de places à libérer est valide
                if ($initialReservedSeats > $salle->getPlacesOccupees()) {
                    $this->addFlash('error', 'Impossible de libérer plus de places qu\'occupées.');
                    return $this->render('reservations/edit.html.twig', [
                        'reservation' => $reservation,
                        'form' => $form->createView(),
                    ]);
                }

                // Rétablir les places initiales avant de recalculer
                $salle->libererPlaces($initialReservedSeats);

                $newRequestedSeats = $reservation->getNombrePlaces();

                // Validation du nouveau nombre de places demandées
                $placesDisponibles = $salle->getNombreSiege()
                    - $salle->getPlacesOccupees()
                    - $salle->getNombreSiegePMR();

                if ($newRequestedSeats > $placesDisponibles) {
                    $this->addFlash('error', sprintf(
                        'Modification impossible : vous demandez %d places, mais seulement %d sont disponibles.',
                        $newRequestedSeats,
                        $placesDisponibles
                    ));
                    return $this->render('reservations/edit.html.twig', [
                        'reservation' => $reservation,
                        'form' => $form->createView(),
                    ]);
                }

                // Réserver les nouvelles places
                $salle->reservePlaces($newRequestedSeats);

                // Mettre à jour l'entité Reservation
                $reservation->setPrixTotal($reservation->calculprixTotal($reservation->getSeances()));

                $entityManager->persist($salle);
                $entityManager->persist($reservation);
                $entityManager->flush();

                $this->addFlash('success', 'Réservation modifiée avec succès.');
                return $this->redirectToRoute('app_reservations_index');
            }
        }

        return $this->render('reservations/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservations_delete', methods: ['POST'])]
    public function delete(Request $request, Reservations $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $salle = $reservation->getSeances()->getSalle();

            if ($salle !== null) {
                $salle->libererPlaces($reservation->getNombrePlaces());
                $entityManager->persist($salle);
            }

            $entityManager->remove($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'Réservation supprimée avec succès.');
        }

        return $this->redirectToRoute('app_reservations_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_reservations_show', methods: ['GET'])]
    public function show(Reservations $reservation): Response
    {
        return $this->render('reservations/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }
}