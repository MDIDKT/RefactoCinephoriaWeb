<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CinemasRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CinemasRepository::class)]
#[ApiResource]
class Cinemas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $horaire = null;

    #[ORM\ManyToMany(targetEntity: Films::class, mappedBy: 'cinemas')]
    private Collection $film;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom;
    }

    public function getHoraire(): ?string
    {
        return $this->horaire;
    }

    public function setHoraire(?string $horaire): static
    {
        $this->horaire = $horaire;

        return $this;
    }


    public function getFilm(): Collection
    {
        return $this->film;
    }

    public function addFilm(Films $film): static
    {
        if (!$this->film->contains($film)) {
            $this->film->add($film);
        }

        return $this;
    }

    public function removeFilm(Films $film): static
    {
        if ($this->film->removeElement($film)) {
            $film->removeCinema($this);
        }

        return $this;
    }
}
