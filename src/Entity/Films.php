<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FilmsRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: FilmsRepository::class)]
#[Vich\Uploadable]
#[ApiResource]
#[ApiResource(
    normalizationContext: ['groups' => ['films:read']],
    denormalizationContext: ['groups' => ['films:write']]
)]
class Films
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['films:read', 'films:write'])]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['films:read', 'films:write'])]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $ageMinimum = null;

    #[ORM\Column]
    private ?bool $coupDeCoeur = null;

    #[ORM\Column]
    private ?float $note = null;

    #[ORM\OneToMany(targetEntity: Seance::class, mappedBy: 'films')]
    #[Groups(['films:read', 'films:write'])]
    private Collection $seances;

    #[ORM\Column(length: 255)]
    private ?string $qualite = null;

    #[ORM\OneToMany(targetEntity: Reservations::class, mappedBy: 'films',)]
    private Collection $reservations;

    #[Vich\UploadableField(mapping: 'affiche', fileNameProperty: 'imageName', size: 'imageSize')]
    #[Groups(['films:read', 'films:write'])]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'film')]
    private Collection $avis;

    #[ORM\ManyToMany(targetEntity: Cinemas::class, inversedBy: 'film')]
    #[Groups(['films:read', 'films:write'])]
    private Collection $cinemas;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->cinemas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAgeMinimum(): ?int
    {
        return $this->ageMinimum;
    }

    public function setAgeMinimum(int $ageMinimum): self
    {
        $this->ageMinimum = $ageMinimum;

        return $this;
    }

    public function getQualite(): ?string
    {
        return $this->qualite;
    }

    public function setQualite(string $qualite): self
    {
        $this->qualite = $qualite;

        return $this;
    }

    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function getCinemas(): Collection
    {
        return $this->cinemas;
    }

    public function addCinema(Cinemas $cinema): self
    {
        if (!$this->cinemas->contains($cinema)) {
            $this->cinemas->add($cinema);
            $cinema->addFilm($this);
        }

        return $this;
    }

    public function removeCinema(Cinemas $cinema): self
    {
        if ($this->cinemas->removeElement($cinema)) {
            $cinema->removeFilm($this);
        }

        return $this;
    }

    // Méthodes pour gérer les images
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): self
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            // Mettre la date à jour si un nouveau fichier est chargé
            $this->createdAt = new DateTime('now');
        }

        return $this;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setImageSize(?int $imageSize): self
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    public function getCoupDeCoeur(): ?bool
    {
        return $this->coupDeCoeur;
    }

    public function setCoupDeCoeur(?bool $coupDeCoeur): void
    {
        $this->coupDeCoeur = $coupDeCoeur;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): void
    {
        $this->note = $note;
    }

    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function setAvis(Collection $avis): void
    {
        $this->avis = $avis;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function setReservations(Collection $reservations): void
    {
        $this->reservations = $reservations;
    }

    public function __toString(): string
    {
        return $this->titre;
    }
}