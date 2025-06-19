<?php

namespace App\Service;

use DateTime;

class ReservationService
{
    // generer un QR code pour une reservation
    public function QRCodeGenerator(): string
    {
        return 'QRCode_' . uniqid(prefix: 'QR_', more_entropy: true);
    }

    // calculer le prix total d'une reservation
    public function calculPrixTotal(int $nombrePlace, float $prixParPlace): float
    {
        return $nombrePlace * $prixParPlace;
    }

    // vérifier si la date de réservation est valide
    public function isDateValid(DateTime $date): bool
    {
        $currentDate = new DateTime();
        return $date >= $currentDate;
    }

    // vérifier si le nombre de places est valide
    public function isNombrePlaceValid(int $nombrePlace): bool
    {
        return $nombrePlace > 0 && $nombrePlace <= 10; // Exemple: max 10 places par réservation
    }

    //verifier que la reservation n'est pas déjà faite
    public function isReservationAlreadyMade(int $reservationId, array $existingReservations): bool
    {
        foreach ($existingReservations as $reservation) {
            if ($reservation['id'] === $reservationId) {
                return true;
            }
        }
        return false;
    }
}
