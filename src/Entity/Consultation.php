<?php

  // src/Entity/Consultation.php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    private ?Service $service = null;  // Ensure lowercase 'service'

    #[ORM\Column(length: 255)]
    private ?string $patientIdentifier = null;  // Add the patientIdentifier field

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
}
