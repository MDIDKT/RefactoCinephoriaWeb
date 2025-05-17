<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ReservationsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\User;

#[ORM\Entity(repositoryClass: ReservationsRepository::class)]
#[ApiResource]
class Reservations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Cinemas $cinemas = null;
    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Seance $seances = null;

    #[ORM\Column]
    private ?int $nombrePlaces = null;

    #[ORM\Column]
    private ?float $prixTotal;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Films $films = null;

    /**
     * @var Collection<int, Sieges>
     */
    #[ORM\OneToMany(targetEntity: Sieges::class, mappedBy: 'reservation')]
    private Collection $sieges;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: Salles::class, inversedBy: 'reservations')]
    private ?Salles $salle = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservations')]
    private ?User $user = null;

    public function __construct()
    {
        $this->prixTotal = $this->getNombrePlaces() * 8;
        $this->sieges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCinemas(): ?Cinemas
    {
        return $this->cinemas;
    }

    public function setCinemas(?Cinemas $cinemas): static
    {
        $this->cinemas = $cinemas;

        return $this;
    }

    public function getSeances(): ?Seance
    {
        return $this->seances;
    }

    public function setSeances(?Seance $seances): static
    {
        $this->seances = $seances;

        return $this;
    }

    public function getNombrePlaces(): ?int
    {
        return $this->nombrePlaces;
    }

    public function setNombrePlaces(int $nombrePlaces): static
    {
        $this->nombrePlaces = $nombrePlaces;

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

    public function getFilms(): ?Films
    {
        return $this->films;
    }

    public function setFilms(?Films $films): static
    {
        $this->films = $films;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNombrePlaces();
    }

    /**
     * @return Collection<int, Sieges>
     */
    public function getSieges(): Collection
    {
        return $this->sieges;
    }

    public function addSiege(Sieges $siege): static
    {
        if (!$this->sieges->contains($siege)) {
            $this->sieges->add($siege);
            $siege->setReservation($this);
        }

        return $this;
    }

    public function removeSiege(Sieges $siege): static
    {
        if ($this->sieges->removeElement($siege)) {
            // set the owning side to null (unless already changed)
            if ($siege->getReservation() === $this) {
                $siege->setReservation(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function calculprixTotal($seance): float
    {
        // Vérifie que la séance est bien définie
        if ($seance === null) {
            throw new \LogicException('Une séance doit être définie pour calculer le prix total.');
        }

        // Configure les prix selon la qualité
        $qualite = $seance->getQualite();
        $prixParPlace = match ($qualite) {
            '3D' => 8.0,
            '4K' => 12.0,
            default => 10.0, // Valeur par défaut pour les autres qualités
        };

        // Vérifie que le nombre de places est valide
        $nombrePlaces = $this->getNombrePlaces();
        if ($nombrePlaces <= 0) {
            throw new \InvalidArgumentException('Le nombre de places doit être supérieur à 0.');
        }

        return $prixParPlace * $nombrePlaces;
    }

    public function getSalle(): ?Salles
    {
        return $this->salle;
    }

    public function setSalle(?Salles $salle): void
    {
        $this->salle = $salle;
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

}
