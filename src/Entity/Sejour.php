<?php

namespace App\Entity;

use App\Repository\SejourRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SejourRepository::class)]
class Sejour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $dateEntree = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(propertyPath: "dateEntree")]
    private ?\DateTimeInterface $dateSortie = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $typeSejour = null;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    private ?float $fraisSejour = null;

    #[ORM\Column(length: 255)]
    private ?string $moyenPaiement = null;

    #[ORM\Column(length: 255)]
    private ?string $statutPaiement = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Assert\PositiveOrZero]
    private ?float $prixExtras = null;

    #[ORM\ManyToOne(inversedBy: 'sejours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DossierMedicale $dossierMedicale = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEntree(): ?\DateTimeInterface
    {
        return $this->dateEntree;
    }

    public function setDateEntree(\DateTimeInterface $dateEntree): static
    {
        $this->dateEntree = $dateEntree;
        return $this;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTimeInterface $dateSortie): static
    {
        $this->dateSortie = $dateSortie;
        return $this;
    }

    public function getTypeSejour(): ?string
    {
        return $this->typeSejour;
    }

    public function setTypeSejour(string $typeSejour): static
    {
        $this->typeSejour = $typeSejour;
        return $this;
    }

    public function getFraisSejour(): ?float
    {
        return $this->fraisSejour;
    }

    public function setFraisSejour(float $fraisSejour): static
    {
        $this->fraisSejour = $fraisSejour;
        return $this;
    }

    public function getMoyenPaiement(): ?string
    {
        return $this->moyenPaiement;
    }

    public function setMoyenPaiement(string $moyenPaiement): static
    {
        $this->moyenPaiement = $moyenPaiement;
        return $this;
    }

    public function getStatutPaiement(): ?string
    {
        return $this->statutPaiement;
    }

    public function setStatutPaiement(string $statutPaiement): static
    {
        $this->statutPaiement = $statutPaiement;
        return $this;
    }

    public function getPrixExtras(): ?float
    {
        return $this->prixExtras;
    }

    public function setPrixExtras(?float $prixExtras): static
    {
        $this->prixExtras = $prixExtras;
        return $this;
    }

    public function getDossierMedicale(): ?DossierMedicale
    {
        return $this->dossierMedicale;
    }

    public function setDossierMedicale(?DossierMedicale $dossierMedicale): static
    {
        $this->dossierMedicale = $dossierMedicale;
        return $this;
    }
} 