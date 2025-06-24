<?php

namespace App\Entity;

use App\Entity\Traits\IDTrait;
use App\Entity\Traits\TimestampTrait;
use App\Enum\ReservationStatus;
use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Reservation
{
    use IDTrait;
    use TimestampTrait;

    /**
     * @var User|null
     * @ORM\ManyToOne(inversedBy="reservations")
     * @Assert\NotBlank()
     * @Assert\Valid()
     * Cette propriété représente l’utilisateur qui a effectué la réservation.
     */
    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[Assert\NotBlank]
    #[Assert\Valid]
    private ?User $user = null;

    /**
     * @var Seance|null
     * @ORM\ManyToOne(inversedBy="reservations")
     * @Assert\NotBlank()
     * @Assert\Valid()
     * Cette propriété représente la séance pour laquelle la réservation est effectuée.
     */
    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[Assert\NotBlank]
    #[Assert\Valid]
    private ?Seance $seance = null;

    /**
     * @var int|null
     * Cette propriété représente le nombre de places réservées.
     * Elle doit être comprise entre 1 et 10.
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Range(
        notInRangeMessage: 'Le nombre de places doit être compris entre {{ min }} et {{ max }}.',
        min: 1,
        max: 10
    )
    ]
    private ?int $nombrePlace = null;

    /**
     * @var float|null
     * Cette propriété représente le prix total de la réservation.
     * Elle doit être supérieure à 0.01.
     */
    #[ORM\Column]
    #[Assert\Range(notInRangeMessage: 'Le prix total doit être supérieur à {{ min }}.', min: 0.01)]
    private ?float $prixTotal = null;

    /**
     * @var string|null
     * Cette propriété représente le code QR associé à la réservation.
     * Elle doit être une chaîne de caractères non vide.
     */
    #[ORM\Column(length: 255)]
    private ?string $qrCode = null;

    /**
     * @var ReservationStatus|null
     * Cette propriété représente le statut de la réservation.
     * Elle doit être une instance de l’énumération ReservationStatus.
     */
    #[ORM\Column(enumType: ReservationStatus::class)]
    #[Assert\NotBlank]
    #[Assert\Valid]
    private ?ReservationStatus $status = null;

    /**
     * @var Collection<int, Siege>
     */
    #[ORM\OneToMany(targetEntity: Siege::class, mappedBy: 'reservation')]
    private Collection $sieges;

    public function __construct()
    {
        $this->sieges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSeance(): ?Seance
    {
        return $this->seance;
    }

    public function setSeance(?Seance $Seance): static
    {
        $this->seance = $Seance;

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

    public function getPrixTotal(): ?float
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(float $prixTotal): static
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    public function getQrCode(): ?string
    {
        return $this->qrCode;
    }

    public function setQrCode(string $qrCode): static
    {
        $this->qrCode = $qrCode;

        return $this;
    }

    public function getStatus(): ?ReservationStatus
    {
        return $this->status;
    }

    public function setStatus(ReservationStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Siege>
     */
    public function getSieges(): Collection
    {
        return $this->sieges;
    }

    public function addSiege(Siege $siege): static
    {
        if (!$this->sieges->contains($siege)) {
            $this->sieges->add($siege);
            $siege->setReservation($this);
        }

        return $this;
    }

    public function removeSiege(Siege $siege): static
    {
        if ($this->sieges->removeElement($siege)) {
            // set the owning side to null (unless already changed)
            if ($siege->getReservation() === $this) {
                $siege->setReservation(null);
            }
        }

        return $this;
    }
}
