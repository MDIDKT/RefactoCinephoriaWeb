<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeanceRepository::class)]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?DateTimeImmutable $heureDebut = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $heureFin = null;

    #[ORM\ManyToOne(inversedBy: 'seance')]
    private ?Cinema $cinema = null;

    #[ORM\ManyToOne(inversedBy: 'seance')]
    private ?Salle $salle = null;

    #[ORM\ManyToOne(inversedBy: 'seance')]
    private ?Film $film = null;

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
}
