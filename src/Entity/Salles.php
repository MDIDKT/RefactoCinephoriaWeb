<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Salles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private ?int $nombrePlaces = null;

    #[ORM\Column(type: 'integer')]
    private int $nombreSiege;

    #[ORM\Column(type: 'integer')]
    private int $nombreSiegePMR;

    #[ORM\Column(type: 'integer')]
    private int $numeroSalle;

    #[ORM\Column(type: 'integer')]
    private int $nombrePlacesDisponibles;

    #[ORM\Column(type: 'integer')]
    private int $placesOccupees = 0; // Ajout de la propriété manquante

    #[ORM\OneToMany(targetEntity: Reservations::class, mappedBy: 'salle', cascade: ['persist', 'remove'])]
    private Collection $reservations;

    #[ORM\OneToMany(targetEntity: Incidents::class, mappedBy: 'salle')]
    private Collection $incidents;

    #[ORM\OneToMany(targetEntity: Seance::class, mappedBy: 'salle')]
    private Collection $seances;

    public function __construct()
    {
        $this->incidents = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->seances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombrePlaces(): ?int
    {
        return $this->nombrePlaces;
    }

    public function setNombrePlaces(int $nombrePlaces): self
    {
        $this->nombrePlaces = $nombrePlaces;

        return $this;
    }

    public function getNombreSiege(): int
    {
        return $this->nombreSiege;
    }

    public function setNombreSiege(int $nombreSiege): self
    {
        $this->nombreSiege = $nombreSiege;

        return $this;
    }

    public function getNombreSiegePMR(): int
    {
        return $this->nombreSiegePMR;
    }

    public function setNombreSiegePMR(int $nombreSiegePMR): self
    {
        $this->nombreSiegePMR = $nombreSiegePMR;

        return $this;
    }

    public function getPlacesOccupees(): int
    {
        return $this->placesOccupees;
    }

    public function setPlacesOccupees(int $places): self
    {
        $this->placesOccupees = $places;

        return $this;
    }

    public function libererPlaces(int $nombre): self
    {
        // Assurez-vous qu'on ne libère pas plus de places qu'occupées
        if ($nombre > $this->placesOccupees) {
            throw new \InvalidArgumentException('Impossible de libérer plus de places qu\'occupées.');
        }

        $this->placesOccupees -= $nombre;

        return $this;
    }

    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservations $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setSalle($this);
        }

        return $this;
    }

    public function removeReservation(Reservations $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // Vérifie l'association avant suppression
            if ($reservation->getSalle() === $this) {
                $reservation->setSalle(null);
            }
        }

        return $this;
    }

    public function getIncidents(): Collection
    {
        return $this->incidents;
    }

    public function addIncident(Incidents $incident): self
    {
        if (!$this->incidents->contains($incident)) {
            $this->incidents->add($incident);
            $incident->setSalle($this);
        }

        return $this;
    }

    public function removeIncident(Incidents $incident): self
    {
        if ($this->incidents->removeElement($incident)) {
            // Vérifie l'association avant suppression
            if ($incident->getSalle() === $this) {
                $incident->setSalle(null);
            }
        }

        return $this;
    }

    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): self
    {
        if (!$this->seances->contains($seance)) {
            $this->seances->add($seance);
            $seance->setSalle($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): self
    {
        if ($this->seances->removeElement($seance)) {
            // Vérifie l'association avant suppression
            if ($seance->getSalle() === $this) {
                $seance->setSalle(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('Salle %s', $this->id);
    }

    public function getNumeroSalle(): int
    {
        return $this->numeroSalle;
    }

    public function setNumeroSalle(int $numeroSalle): void
    {
        $this->numeroSalle = $numeroSalle;
    }

    public function getNombrePlacesDisponibles(): int
    {
        return max(0, $this->nombreSiege - $this->placesOccupees - $this->nombreSiegePMR);
    }

    public function setNombrePlacesDisponibles(int $nombrePlacesDisponibles): void
    {
        $this->nombrePlacesDisponibles = $nombrePlacesDisponibles;
    }

    public function reservePlaces(int $nombre): self
    {
        // Vérifiez si vous pouvez réserver ce nombre de places
        $placesDisponibles = $this->getNombrePlacesDisponibles();

        if ($nombre > $placesDisponibles) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Impossible de réserver %d places. Seulement %d places disponibles.',
                    $nombre,
                    $placesDisponibles
                )
            );
        }

        $this->placesOccupees += $nombre;

        return $this;
    }
}