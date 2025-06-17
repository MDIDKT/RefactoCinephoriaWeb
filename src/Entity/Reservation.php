<?php

namespace App\Entity;

use App\Entity\Traits\IDTrait;
use App\Entity\Traits\TimestampTrait;
use App\Repository\ReservationRepository;
use App\Service\ReservationService;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Reservation extends ReservationService
{
    use IDTrait;
    use TimestampTrait;

    #[ORM\Column]
    private ?int $nombrePlace = null;

    #[ORM\Column]
    private ?float $prixTotal = null;

    #[ORM\Column]
    private ?DateTime $date = null;

    #[ORM\Column(nullable: true)]
    private ?bool $status = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Cinema $cinema = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Salle $salle = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Film $film = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Seance $seance = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $QRCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombrePlace(): ?int
    {
        return $this->nombrePlace;
    }

    public function setNombrePlace(int $nombrePlace): static
    {
        $this->nombrePlace = $nombrePlace;

        return $this;
    }

    public function getPrixTotal(): ?float
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(float $prixTotal): static
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCinema(): ?Cinema
    {
        return $this->cinema;
    }

    public function setCinema(?Cinema $cinema): static
    {
        $this->cinema = $cinema;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): static
    {
        $this->salle = $salle;

        return $this;
    }

    public function getFilm(): ?Film
    {
        return $this->film;
    }

    public function setFilm(?Film $film): static
    {
        $this->film = $film;

        return $this;
    }

    public function getSeance(): ?Seance
    {
        return $this->seance;
    }

    public function setSeance(?Seance $seance): static
    {
        $this->seance = $seance;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getQRCode(): ?string
    {
        return $this->QRCode;
    }

    public function setQRCode(?string $QRCode): static
    {
        $this->QRCode = $QRCode;

        return $this;
    }
}
