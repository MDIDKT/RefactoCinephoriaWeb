<?php

namespace App\Entity;

use App\Enum\IncidentStatus;
use App\Repository\IncidentRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IDTrait;
use App\Entity\Traits\TimestampTrait;

#[ORM\Entity(repositoryClass: IncidentRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Incident
{
    use IDTrait;
    use TimestampTrait;

    #[ORM\ManyToOne(inversedBy: 'incidents')]
    private ?User $Employee = null;

    #[ORM\ManyToOne(inversedBy: 'incidents')]
    private ?Salle $salle = null;

    #[ORM\Column]
    private ?DateTimeImmutable $date = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', enumType: IncidentStatus::class, options: ['default' => IncidentStatus::OPEN->value])]
    private IncidentStatus $status = IncidentStatus::OPEN;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?User
    {
        return $this->Employee;
    }

    public function setEmployee(?User $Employee): static
    {
        $this->Employee = $Employee;

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

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): IncidentStatus
    {
        return $this->status;
    }

    public function setStatus(IncidentStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getIncidents(Salle $salle)
    {
    }
}
