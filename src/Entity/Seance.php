<?php

namespace App\Entity;

use App\Entity\Traits\IDTrait;
use App\Repository\SeanceRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeanceRepository::class)]
class Seance
{
    use IDTrait;

    #[ORM\Column]
    private ?DateTimeImmutable $heureDebut = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $heureFin = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    private ?Cinema $cinema = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    private ?Salle $salle = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    private ?Film $film = null;

    #[ORM\Column]
    private ?float $prixPlace = 10.0;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'seance')]
    private Collection $reservations;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $date = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeureDebut(): ?DateTimeImmutable
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(DateTimeImmutable $heureDebut): static
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    public function getHeureFin(): ?DateTimeImmutable
    {
        return $this->heureFin;
    }

    public function setHeureFin(?DateTimeImmutable $heureFin): static
    {
        $this->heureFin = $heureFin;

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

    public function getPrixPlace(): ?float
    {
        return $this->prixPlace;
    }

    public function setPrixPlace(float $prixPlace): static
    {
        $this->prixPlace = $prixPlace;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setSeance($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getSeance() === $this) {
                $reservation->setSeance(null);
            }
        }

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
}
