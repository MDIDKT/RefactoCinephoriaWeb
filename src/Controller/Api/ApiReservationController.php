<?php

//ici ce trouve le code de l'api de reservation
namespace App\Controller\Api;

use App\Entity\Reservations;
use App\Form\ReservationsType;
use App\Repository\ReservationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 *
 */
#[Route('/api/reservations')]
class ApiReservationController extends AbstractController
{
    #[Route('/', name: 'api_reservations_index', methods: ['GET'])]
    public function index(ReservationsRepository $reservationsRepository): Response
    {
        return $this->json($reservationsRepository->findAll(), 200, [], ['groups' => 'reservations:read']);
    }

    #[Route('/{id}', name: 'api_reservations_show', methods: ['GET'])]
    public function show(Reservations $reservation): Response
    {
        return $this->json($reservation, 200, [], ['groups' => 'reservations:read']);
    }

    #[Route('/new', name: 'api_reservations_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservations();
        $form = $this->createForm(ReservationsType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->json($reservation, 201, [], ['groups' => 'reservations:read']);
        }

        return $this->json($form->getErrors(true, false), 400);
    }

    #[Route('/{id}', name: 'api_reservations_delete', methods: ['DELETE'])]
    public function delete(Reservations $reservation, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($reservation);
        $entityManager->flush();

        return $this->json(null, 204);
    }

    #[Route('/{id}', name: 'api_reservations_update', methods: ['PUT'])]
    public function update(Reservations $reservation, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationsType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->json($reservation, 200, [], ['groups' => 'reservations:read']);
        }

        return $this->json($form->getErrors(true, false), 400);
    }
}
