<?php

namespace App\Tests\Repository;

use App\Entity\Reservation;
use DateTime;
use PHPUnit\Framework\TestCase;

class ReservationRepositoryTest extends TestCase
{
    public function testCreerReservation(): void
    {
        $reservation = new Reservation();
        $reservation->setNombrePlace(2);
        $reservation->setStatus(true);
        $reservation->setDate(new DateTime());
        $reservation->setPrixTotal(20.00);
        $reservation->setQrCode('test-qr-code');

        // Vérifier que l'entité est correctement créée
        $this->assertInstanceOf(Reservation::class, $reservation);
        $this->assertEquals(2, $reservation->getNombrePlace());
        $this->assertTrue($reservation->isStatus());
        $this->assertInstanceOf(DateTime::class, $reservation->getDate());
        $this->assertEquals(20.00, $reservation->getPrixTotal());
        $this->assertEquals('test-qr-code', $reservation->getQrCode());
    }

    public function testMettreAJourReservation(): void
    {
        $reservation = new Reservation();
        $reservation->setNombrePlace(2);
        $reservation->setStatus(true);
        $reservation->setDate(new DateTime());
        $reservation->setPrixTotal(20.00);
        $reservation->setQrCode('test-qr-code');

        // Mise à jour des valeurs
        $reservation->setNombrePlace(3);
        $reservation->setStatus(false);
        $reservation->setPrixTotal(30.00);
        $reservation->setQrCode('updated-qr-code');

        // Vérifier que les valeurs sont mises à jour
        $this->assertEquals(3, $reservation->getNombrePlace());
        $this->assertFalse($reservation->isStatus());
        $this->assertEquals(30.00, $reservation->getPrixTotal());
        $this->assertEquals('updated-qr-code', $reservation->getQrCode());
    }

    public function testReservationExiste(): void
    {
        $reservation = new Reservation();
        $reservation->setNombrePlace(2);
        $reservation->setStatus(true);
        $reservation->setDate(new DateTime());
        $reservation->setPrixTotal(20.00);
        $reservation->setQrCode('test-qr-code');

        // Vérifier que l'entité est une instance de Reservation
        $this->assertInstanceOf(Reservation::class, $reservation);

        // Vérifier que les valeurs sont correctes
        $this->assertEquals(2, $reservation->getNombrePlace());
        $this->assertTrue($reservation->isStatus());
        $this->assertInstanceOf(DateTime::class, $reservation->getDate());
        $this->assertEquals(20.00, $reservation->getPrixTotal());
        $this->assertEquals('test-qr-code', $reservation->getQrCode());
    }
}
