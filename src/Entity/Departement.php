<?php

namespace App\Entity;

use App\Repository\DepartementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DepartementRepository::class)]
class Departement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    #[Assert\Length(
        min: 3, 
        max: 255, 
        minMessage: "Le nom doit contenir au moins 3 caractères.",
        maxMessage: "Le nom ne peut pas dépasser 255 caractères."
    )]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse ne peut pas être vide.")]
    private ?string $Adresse = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Le nombre d'étages est obligatoire.")]
    #[Assert\Positive(message: "Le nombre d'étages doit être un nombre positif.")]
    private ?int $nbrEtage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): static
    {
        $this->Adresse = $Adresse;
        return $this;
    }

    public function getNbrEtage(): ?int
    {
        return $this->nbrEtage;
    }

    public function setNbrEtage(int $nbrEtage): static
    {
        $this->nbrEtage = $nbrEtage;
        return $this;
    }
}
