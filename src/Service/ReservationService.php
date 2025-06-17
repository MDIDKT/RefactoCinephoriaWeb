<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\Salle;
use App\Entity\Seance;
use App\Repository\ReservationRepository;
use DateTime;
use Exception;

class ReservationService
{
    public function __construct(ReservationRepository $reservationRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function creerReservation(
        Seance $seance,
        Salle $salle,
        int $nombrePlaces,
        ReservationRepository $reservationRepository
    ): Reservation {
        if (!$this->verifierPlacesDisponibles($seance, $nombrePlaces, $salle)) {
            throw new Exception('Pas assez de places disponibles');
        }

        $reservation = new Reservation($reservationRepository);
        $reservation->setSeance($seance);
        $reservation->setSalle($salle);
        $reservation->setNombrePlace($nombrePlaces);
        $reservation->setPrixTotal($this->calculerPrixTotal($seance, $nombrePlaces));
        $reservation->setDate(new DateTime());
        $reservation->setQRCode($this->genererQRCode($reservation));

        // Enregistrer la réservation
        $reservationRepository->save($reservation);

        return $reservation;
    }

    public function verifierPlacesDisponibles(Seance $seance, int $nombreDemande, Salle $salle): bool
    {
        // Retourne true si le nombre de places est dispo
        $placesDisponibles = $salle->getNombrePlace() - $seance->getReservations()->count();
        return $placesDisponibles >= $nombreDemande;
    }

    //genère le QRCode pour la réservation

    public function calculerPrixTotal(Seance $seance, int $nombrePlaces): float
    {
        // Calcule le prix total de la réservation
        $prixUnitaire = $seance->getFilm()->getPrix(); // Supposons que le film a un prix
        return $prixUnitaire * $nombrePlaces;
    }

    // Faire une nouvelle réservation

    public function genererQRCode(Reservation $reservation): string
    {
        // Logique pour générer un QRCode
        // Retourne le chemin ou l'URL du QRCode généré
        return 'path/to/qrcode/' . $reservation->getId() . '.png';
    }
}
