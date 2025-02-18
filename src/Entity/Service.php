<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: " Nom est obligatoire.")]
    #[Assert\Length(
        min: 3,
        minMessage: "Le nom doit comporter au moins {{ limit }} caractères.",
        max: 255,
        maxMessage: "The name cannot be longer than {{ limit }} characters."
    )]
    #[Assert\Regex(
        pattern: "/^[\p{L}\s'-]+$/u",
        message:  "Le nom ne doit contenir que des lettres, des espaces, des traits d'union et des apostrophes."
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Description est obligatoire.")]
    #[Assert\Length(
        min: 10,
        minMessage: "La description doit comporter au moins {{ limit }} caractères.",
        max: 255,
        maxMessage: "The description cannot be longer than {{ limit }} characters."
    )]
    private ?string $description = null;

    #[ORM\Column(type: "integer")]
    #[Assert\NotNull(message: "Duration est obligatoire.")]
    #[Assert\Positive(message: "La durée doit être une valeur positive.")]
    private ?int $duration = null;

    /**
     * @var Collection<int, Consultation>
     */
    #[ORM\OneToMany(targetEntity: Consultation::class, mappedBy: 'service', cascade: ['persist', 'remove'])]
    private Collection $status;

    public function __construct()
    {
        $this->status = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name !== null ? trim($name) : null;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description !== null ? trim($description) : null;
        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration !== null ? max(1, $duration) : null;
        return $this;
    }

    /**
     * @return Collection<int, Consultation>
     */
    public function getStatus(): Collection
    {
        return $this->status;
    }

    public function addStatus(Consultation $status): static
    {
        if (!$this->status->contains($status)) {
            $this->status->add($status);
            $status->setService($this);
        }

        return $this;
    }

    public function removeStatus(Consultation $status): static
    {
        if ($this->status->removeElement($status)) {
            if ($status->getService() === $this) {
                $status->setService(null);
            }
        }

        return $this;
    }
}
