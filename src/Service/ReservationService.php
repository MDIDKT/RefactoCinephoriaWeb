<?php

namespace App\Service;

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
}
