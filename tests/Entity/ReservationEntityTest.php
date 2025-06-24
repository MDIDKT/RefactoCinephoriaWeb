<?php

namespace App\Tests\Entity;

use App\Entity\Reservation;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ReservationEntityTest extends KernelTestCase
{
    public function testEntityisValid(): void
    {
        $reservation = new Reservation();
        $reservation->setNombrePlace(2);
        $reservation->setStatus(true);
        $reservation->setDate(new DateTime());
        $reservation->setPrixTotal(20.00);
        $reservation->setQrCode('test-qr-code');
        static::assertInstanceOf(Reservation::class, $reservation);
        static::assertEquals(2, $reservation->getNombrePlace());
        static::assertTrue($reservation->isStatus());
        static::assertInstanceOf(DateTime::class, $reservation->getDate());
        static::assertEquals(20.00, $reservation->getPrixTotal());
        static::assertEquals('test-qr-code', $reservation->getQrCode());
    }
}
