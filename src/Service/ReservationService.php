<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;

final readonly class ReservationService
{
    public function __construct(private ReservationRepository $reservationRepository)
    {
    }

    public function getAllReservations(): array
    {
        return $this->reservationRepository->findAll();
    }

    public function getReservationById(int $id): ?Reservation
    {
        return $this->reservationRepository->find($id);
    }

    public function createReservation(Reservation $reservation): void
    {
        $this->reservationRepository->save($reservation, true);
    }

    public function deleteReservation(Reservation $reservation): void
    {
        $this->reservationRepository->remove($reservation, true);
    }
}
