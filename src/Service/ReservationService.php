<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Repository\SeanceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ReservationService
{
    private EntityManagerInterface $entityManager;
    private ReservationRepository $reservationRepository;
    private UserRepository $userRepository;
    private SeanceRepository $seanceRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ReservationRepository $reservationRepository,
        UserRepository $userRepository,
        SeanceRepository $seanceRepository
    ) {
        $this->entityManager = $entityManager;
        $this->reservationRepository = $reservationRepository;
        $this->userRepository = $userRepository;
        $this->seanceRepository = $seanceRepository;
    }

    /**
     * Crée une nouvelle réservation pour un utilisateur et une séance donnés.
     *
     * @param int $utilisateurId L'ID de l'utilisateur.
     * @param int $seanceId L'ID de la séance.
     * @param int $places Le nombre de places réservées.
     * @return Reservation La réservation créée.
     * @throws Exception Si l'utilisateur ou la séance n'existe pas, ou si une réservation existe déjà.
     */
    public function creerReservationService(int $utilisateurId, int $seanceId, int $places): Reservation
    {
        $user = $this->userRepository->find($utilisateurId);
        $seance = $this->seanceRepository->find($seanceId);
        if (!$user || !$seance) {
            throw new Exception('Utilisateur ou séance introuvable.');
        }

        $existingReservation = $this->reservationRepository->findOneBy([
            'user' => $user,
            'seance' => $seance,
        ]);

        if ($existingReservation) {
            throw new Exception('Vous avez déjà une réservation pour cette séance.');
        }

        $reservation = new Reservation();
        $reservation->setUser($user)
            ->setSeance($seance)
            ->setNombrePlace($places);

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return $reservation;
    }

    /**
     * Calcule le prix total d'une réservation en fonction de la séance, de la qualité et du nombre de places.
     *
     * @param int $seanceId L'ID de la séance.
     * @param string $qualite La qualité de la place (par exemple, "standard", "vip").
     * @param int $nombrePlaces Le nombre de places réservées.
     * @return float Le prix total calculé.
     * @throws Exception Si la séance n'existe pas.
     */

    public function calculerPrixTotalService(int $seanceId, string $qualite, int $nombrePlaces): float
    {
        $seance = $this->seanceRepository->find($seanceId);
        if (!$seance) {
            throw new Exception('Séance introuvable.');
        }
        // Exemple: si la qualité est "vip", multiplier le prix par 1.5
        $multiplicateur = ($qualite === 'vip') ? 1.5 : 1;
        $prixUnitaire = $seance->getPrixPlace();

        return $prixUnitaire * $nombrePlaces * $multiplicateur;
    }

    /**
     * Attribue un nombre de places à une réservation existante.
     *
     * @param int $reservationId L'ID de la réservation.
     * @param int $places Le nombre de places à attribuer.
     * @return Reservation La réservation mise à jour.
     * @throws Exception Si la réservation n'existe pas.
     */
    public function attribuerPlacesService(int $reservationId, int $places): Reservation
    {
        $reservation = $this->reservationRepository->find($reservationId);
        if (!$reservation) {
            throw new Exception('Réservation introuvable.');
        }
        // On pourrait vérifier ici la disponibilité des sièges
        $reservation->setPlaces($places);
        $this->entityManager->flush();

        return $reservation;
    }

    /**
     * Confirme une réservation existante.
     *
     * @param int $reservationId L'ID de la réservation.
     * @return Reservation La réservation confirmée.
     * @throws Exception Si la réservation n'existe pas.
     */

    public function confirmerReservationService(int $reservationId): Reservation
    {
        $reservation = $this->reservationRepository->find($reservationId);
        if (!$reservation) {
            throw new Exception('Réservation introuvable.');
        }
        $reservation->setStatus('confirmed');
        $this->entityManager->flush();

        return $reservation;
    }

    /**
     * Récupère l'historique des réservations d'un utilisateur.
     *
     * @param int $utilisateurId L'ID de l'utilisateur.
     * @return array Un tableau de réservations de l'utilisateur.
     * @throws Exception Si l'utilisateur n'existe pas.
     */

    public function historiqueReservationsService(int $utilisateurId): array
    {
        $user = $this->userRepository->find($utilisateurId);
        if (!$user) {
            throw new Exception('Utilisateur introuvable.');
        }
        return $this->reservationRepository->findBy(['user' => $user]);
    }
}
