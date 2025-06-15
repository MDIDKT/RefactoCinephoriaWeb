<?php

namespace App\Service;

use App\Repository\ReservationRepository;
use App\Entity\Reservation;
use App\Entity\Seance;
use App\Entity\User;

class ReservationService
{
    public function __construct(private readonly ReservationRepository $reservationRepository) {}

    public function verifierPlacesDisponibles(Seance $seance, int $nombreDemandé): bool
    {
        // Retourne true si le nombre de places est dispo
    }

    public function calculerPrix(int $qualiteId, int $nombrePlaces): float
    {
        // Calcule le prix total en fonction de la qualité
    }

    public function confirmerReservation(User $user, Seance $seance, int $places): Reservation
    {
        // Crée la réservation, enregistre, déclenche le QRCode
    }

    public function genererQrCode(Reservation $reservation): string
    {
        // Génère un code QR unique pour la réservation
    }
}
