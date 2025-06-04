<?php

namespace App\Service;

use App\Entity\Seance;
use InvalidArgumentException;
use LogicException;

class ReservationsService
{
    private const array PRIX_PAR_QUALITE = [
        '3D' => 8.0,
        '4K' => 12.0,
        'standard' => 10.0
    ];

    public function calculerPrixTotal(?Seance $seance, int $nombrePlaces): float
    {
        if (null === $seance) {
            throw new LogicException('Une séance doit être définie pour calculer le prix total.');
        }

        if ($nombrePlaces <= 0) {
            throw new InvalidArgumentException('Le nombre de places doit être supérieur à 0.');
        }

        $qualite = $seance->getQualite();
        $prixParPlace = self::PRIX_PAR_QUALITE[$qualite] ?? self::PRIX_PAR_QUALITE['standard'];

        return $prixParPlace * $nombrePlaces;
    }

    public function getPrixParQualite(string $qualite): float
    {
        return self::PRIX_PAR_QUALITE[$qualite] ?? self::PRIX_PAR_QUALITE['standard'];
    }

    public function getQualitesDisponibles(): array
    {
        return array_keys(self::PRIX_PAR_QUALITE);
    }
}