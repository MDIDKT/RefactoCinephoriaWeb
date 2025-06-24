<?php

namespace App\Entity;

use App\Entity\Traits\IDTrait;
use App\Repository\SiegeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiegeRepository::class)]
class Siege
{
    use IDTrait;

    /**
     * @var int|null
     * Cette proprité permet d'indiqué le numero du siege
     */
    #[ORM\Column]
    private ?int $numero = null;

    /**
     * @var bool|null
     * Cette propriété indique si le siege est PMR ou non
     */
    #[ORM\Column(nullable: true)]
    private ?bool $PMR = null;

    /**
     * @var Reservation|null
     * Cette propriétéest relié à une reservation
     */
    #[ORM\ManyToOne(inversedBy: 'sieges')]
    private ?Reservation $reservation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function isPMR(): ?bool
    {
        return $this->PMR;
    }

    public function setPMR(?bool $PMR): static
    {
        $this->PMR = $PMR;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }
}
