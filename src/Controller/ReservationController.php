<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationForm;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reservation')]
final class ReservationController extends AbstractController
{
    #[Route(name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationRepository = $entityManager->getRepository(Reservation::class);
        $form = $this->createForm(ReservationForm::class, new Reservation($reservationRepository));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //calcul du prix total
            $reservation = $form->getData();
            $reservation->setPrixTotal($reservation->getNombrePlace() * $reservation->getPrixTotal());
            //génération du QRCode
            $reservation->setQRCode('QRCode_' . uniqid()); // Placeholder for QR code generation logic
            // Enregistrement de la réservation
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => new Reservation($reservationRepository),
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationForm::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mise à jour du prix total
            $reservation->setPrixTotal($reservation->getNombrePlace() * $reservation->getFilm()->getPrix());
            // Mise à jour du QRCode si nécessaire
            if (!$reservation->getQRCode()) {
                $reservation->setQRCode('QRCode_' . uniqid()); // Placeholder for QR code generation logic
            }
            // Enregistrement des modifications
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
