<?php

namespace App\Tests\service;

use App\Service\ReservationService;
use PHPUnit\Framework\TestCase;

class ReservationServiceTest extends TestCase
{
    // Test de la génération du QR code
    public function testQRCodeGenerator(): void
    {
        $reservationService = new ReservationService();
        $qrCode = $reservationService->QRCodeGenerator();

        // Vérifier que le QR code est une chaîne de caractères
        $this->assertIsString($qrCode);

        // Vérifier que le QR code commence par 'QRCode_'
        $this->assertStringStartsWith('QRCode_', $qrCode);
    }

    // Test du calcul du prix total
    public function testCalculPrixTotal(): void
    {
        $reservationService = new ReservationService();
        $nombrePlace = 3;
        $prixParPlace = 10.00;
        $prixTotal = $reservationService->calculPrixTotal($nombrePlace, $prixParPlace);

        // Vérifier que le prix total est correct
        $this->assertEquals(30.00, $prixTotal);
    }
}
