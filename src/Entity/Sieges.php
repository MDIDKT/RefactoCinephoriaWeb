<?php

namespace App\Entity;

use App\Repository\SiegesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiegesRepository::class)]
class Sieges
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numeroSiege = null;

    #[ORM\Column]
    private ?bool $siegePMR = null;

    #[ORM\ManyToOne(inversedBy: 'sieges')]
    private ?Reservations $reservation = null;

    public function getId (): ?int
    {
        return $this->id;
    }

    public function getNumeroSiege (): ?int
    {
        return $this->numeroSiege;
    }

    public function setNumeroSiege (int $numeroSiege): static
    {
        $this->numeroSiege = $numeroSiege;

        return $this;
    }

    public function isSiegePMR (): ?bool
    {
        return $this->siegePMR;
    }

    public function setSiegePMR (bool $siegePMR): static
    {
        $this->siegePMR = $siegePMR;

        return $this;
    }

    public function getReservation (): ?Reservations
    {
        return $this->reservation;
    }

    public function setReservation (?Reservations $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }
}
