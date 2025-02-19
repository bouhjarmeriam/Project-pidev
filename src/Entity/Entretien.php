<?php

// src/Entity/Entretien.php
namespace App\Entity;

use App\Repository\EntretienRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntretienRepository::class)]
class Entretien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 200)]
    private ?string $descreption = null;

    #[ORM\Column(length: 200)]
    private ?string $nom_equipement = null;

    // Getter pour 'id'
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter pour 'date'
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    // Setter pour 'date'
    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    // Getter pour 'descreption'
    public function getDescreption(): ?string
    {
        return $this->descreption;
    }

    // Setter pour 'descreption'
    public function setDescreption(string $descreption): static
    {
        $this->descreption = $descreption;

        return $this;
    }

    // Getter pour 'nom_equipement'
    public function getNomEquipement(): ?string
    {
        return $this->nom_equipement;
    }

    // Setter pour 'nom_equipement'
    public function setNomEquipement(string $nom_equipement): static
    {
        $this->nom_equipement = $nom_equipement;

        return $this;
    }
}
