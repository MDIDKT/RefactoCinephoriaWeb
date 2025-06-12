<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IDTrait;
use App\Entity\Traits\TimestampTrait;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Salle
{
    use IDTrait;
    use TimestampTrait;

    #[ORM\Column]
    private ?int $numero = null;

    #[ORM\Column]
    private ?int $nombrePlace = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $qualite = null;

    #[ORM\Column]
    private ?int $siege = null;

    #[ORM\Column(nullable: true)]
    private ?int $siegePMR = null;

    /**
     * @var Collection<int, Seance>
     */
    #[ORM\OneToMany(targetEntity: Seance::class, mappedBy: 'salle')]
    private Collection $seances;

    /**
     * @var Collection<int, Incident>
     */
    #[ORM\OneToMany(targetEntity: Incident::class, mappedBy: 'salle')]
    private Collection $incidents;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
        $this->incidents = new ArrayCollection();
    }

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

    public function getNombrePlace(): ?int
    {
        return $this->nombrePlace;
    }

    public function setNombrePlace(int $nombrePlace): static
    {
        $this->nombrePlace = $nombrePlace;

        return $this;
    }

    public function getQualite(): ?string
    {
        return $this->qualite;
    }

    public function setQualite(?string $qualite): static
    {
        $this->qualite = $qualite;

        return $this;
    }

    public function getSiege(): ?int
    {
        return $this->siege;
    }

    public function setSiege(int $siege): static
    {
        $this->siege = $siege;

        return $this;
    }

    public function getSiegePMR(): ?int
    {
        return $this->siegePMR;
    }

    public function setSiegePMR(?int $siegePMR): static
    {
        $this->siegePMR = $siegePMR;

        return $this;
    }

    /**
     * @return Collection<int, Seance>
     */
    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): static
    {
        if (!$this->seances->contains($seance)) {
            $this->seances->add($seance);
            $seance->setSalle($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): static
    {
        if ($this->seances->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getSalle() === $this) {
                $seance->setSalle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Incident>
     */
    public function getIncidents(): Collection
    {
        return $this->incidents;
    }

    public function addIncident(Incident $incident): static
    {
        if (!$this->incidents->contains($incident)) {
            $this->incidents->add($incident);
            $incident->setSalle($this);
        }

        return $this;
    }

    public function removeIncident(Incident $incident): static
    {
        if ($this->incidents->removeElement($incident)) {
            // set the owning side to null (unless already changed)
            if ($incident->getSalle() === $this) {
                $incident->setSalle(null);
            }
        }

        return $this;
    }
}
