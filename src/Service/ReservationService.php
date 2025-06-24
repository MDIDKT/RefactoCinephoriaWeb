<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\Siege;
use App\Enum\ReservationStatus;
use App\Repository\ReservationRepository;
use App\Repository\SeanceRepository;
use App\Repository\SiegeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Throwable;

/**
 * Service principal pour la gestion des réservations.
 * Centralise toute la logique métier lié aux réservations.
 */
class ReservationService
{
    private EntityManagerInterface $em;
    private ReservationRepository $reservationRepository;
    private SiegeRepository $siegeRepository;
    private SeanceRepository $seanceRepository;
    private ?MailerInterface $mailer;
    private ?LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $em,
        ReservationRepository $reservationRepository,
        SiegeRepository $siegeRepository,
        SeanceRepository $seanceRepository,
        ?MailerInterface $mailer = null,
        ?LoggerInterface $logger = null
    ) {
        $this->em = $em;
        $this->reservationRepository = $reservationRepository;
        $this->siegeRepository = $siegeRepository;
        $this->seanceRepository = $seanceRepository;
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    /**
     * Crée une réservation avec gestion transactionnelle.
     *
     * @param int $seanceId
     * @param int[] $siegeIds
     * @param float $prixParPlace
     * @param string $emailUtilisateur
     * @return Reservation
     * @throws Exception|Throwable
     */
    public function creerReservation(
        int $seanceId,
        array $siegeIds,
        float $prixParPlace,
        string $emailUtilisateur
    ): Reservation {
        $this->em->beginTransaction();
        try {
            if (!$this->verifierDisponibiliteSieges($siegeIds)) {
                throw new Exception('Un ou plusieurs sièges ne sont plus disponibles.');
            }

            // Création de la réservation
            $reservation = new Reservation();
            $reservation->setSeance($this->seanceRepository->find($seanceId));
            $reservation->setStatus(ReservationStatus::PENDING);
            $reservation->setQrCode($this->genererQrCode());
            $reservation->setPrixTotal($this->calculerPrixTotal(count($siegeIds), $prixParPlace));
            $reservation->setUser($this->reservationRepository->findByUser($emailUtilisateur));

            // Attribution des sièges à la réservation
            foreach ($siegeIds as $id) {
                $siege = $this->siegeRepository->find($id);
                $siege->setReservation($reservation);
                $this->em->persist($siege);
            }

            $this->em->persist($reservation);
            $this->em->flush();
            $this->em->commit();

            $this->envoiNotificationReservation($emailUtilisateur, $reservation);

            $this->logger?->info("Réservation créée: {$reservation->getId()}", [
                'sieges' => $siegeIds,
                'email' => $emailUtilisateur,
            ]);

            return $reservation;
        } catch (Throwable $e) {
            $this->em->rollback();
            $this->logger?->error('Erreur création réservation', ['exception' => $e]);
            throw $e;
        }
    }

    /**
     * Vérifie la disponibilité des sièges pour une séance donnée.
     *
     * @param array $siegeId
     * @return bool
     */
    public function verifierDisponibiliteSieges(array $siegeId): bool
    {
        foreach ($siegeId as $id) {
            /** @var Siege|null $siege */
            $siege = $this->siegeRepository->find($id);
            if (!$siege || $siege->getReservation() !== null) {
                return false;
            }
        }
        return true;
    }

    /**
     * Génère un QR code unique (ici une chaîne aléatoire).
     *
     * @return string
     */
    public function genererQrCode(): string
    {
        // Pour une vraie génération, intégrer un service de QR Code (ex: endroid/qr-code)
        return 'QR_' . uniqid('', true);
    }

    /**
     * Calcule dynamiquement le prix total selon le nombre de places et le prix par place.
     *
     * @param int $nombreDePlaces
     * @param float $prixUnitaire
     * @param array $remises (optionnel)
     * @return float
     */
    public function calculerPrixTotal(int $nombreDePlaces, float $prixUnitaire, array $remises = []): float
    {
        $total = $nombreDePlaces * $prixUnitaire;
        foreach ($remises as $reduction) {
            $total -= $reduction;
        }
        return max(0, $total);
    }

    /**
     * Envoie un email de confirmation de réservation à l’utilisateur.
     *
     * @param string $emailUtilisateur
     * @param Reservation $reservation
     * @return void
     * @throws TransportExceptionInterface
     */
    private function envoiNotificationReservation(string $emailUtilisateur, Reservation $reservation): void
    {
        if (!$this->mailer) {
            return;
        }
        $email = (new Email())
            ->from('no-reply@cinephoria.fr')
            ->to($emailUtilisateur)
            ->subject('Confirmation de votre réservation')
            ->text('Votre réservation est confirmée. QR Code: ' . $reservation->getQrCode());
        $this->mailer->send($email);
    }

    /**
     * Annule une réservation et libère les sièges associés.
     *
     * @param Reservation $reservation
     * @param string|null $motif
     * @return void
     * @throws TransportExceptionInterface
     */
    public function annulerReservation(Reservation $reservation, ?string $motif = null): void
    {
        $reservation->setStatus(ReservationStatus::CANCELLED);
        foreach ($reservation->getSieges() as $siege) {
            $siege->setReservation(null);
        }
        $this->em->flush();

        $this->envoiNotificationAnnulation($reservation);

        $this->logger?->info("Réservation annulée: {$reservation->getId()}", [
            'motif' => $motif,
        ]);
    }

    /**
     * Envoie une notification email d’annulation à l’utilisateur.
     *
     * @param Reservation $reservation
     * @return void
     * @throws TransportExceptionInterface
     */
    private function envoiNotificationAnnulation(Reservation $reservation): void
    {
        if (!$this->mailer) {
            return;
        }
        $email = (new Email())
            ->from('no-reply@cinephoria.fr')
            ->to($reservation->getUser()->getEmail())
            ->subject('Annulation de votre réservation')
            ->text('Votre réservation a été annulée.');
        $this->mailer->send($email);
    }

    /**
     * Expire une réservation (cas d’une non-validation ou non-paiement).
     *
     * @param Reservation $reservation
     * @return void
     */
    public function expirerReservation(Reservation $reservation): void
    {
        $reservation->setStatus(ReservationStatus::EXPIRED);
        foreach ($reservation->getSieges() as $siege) {
            $siege->setReservation(null);
        }
        $this->em->flush();

        $this->logger?->info("Réservation expirée: {$reservation->getId()}");
    }
}
