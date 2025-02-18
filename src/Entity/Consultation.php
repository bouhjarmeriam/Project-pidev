<?php

   // src/Entity/Consultation.php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    // Define constants for statuses
    const STATUS_IN_PROGRESS = 'En cours de traitement';
    const STATUS_COMPLETED = 'Completed';
    const STATUS_PENDING = 'Pending';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    private ?Service $service = null;

    #[ORM\Column(length: 255)]
   #[Assert\NotBlank(message: 'Patient Identifier cannot be empty')]
    private ?string $patientIdentifier = null;

    #[ORM\Column(length: 50)]
    private ?string $status = self::STATUS_IN_PROGRESS;  // Default status is "En cours de traitement"

    public function getId(): ?int
    {
        return $this->id;
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

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;
        return $this;
    }

    public function getPatientIdentifier(): ?string
    {
        return $this->patientIdentifier;
    }

    public function setPatientIdentifier(string $patientIdentifier): static
    {
        $this->patientIdentifier = $patientIdentifier;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }
}
