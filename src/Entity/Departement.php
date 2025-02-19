<?php

namespace App\Entity;

use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartementRepository::class)]
class Departement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;


    #[ORM\Column]
    private ?int $nbreetage = null;

    /**
     * @var Collection<int, Etage>
     */
    #[ORM\OneToMany(targetEntity: Etage::class, mappedBy: 'departement')]
    private Collection $etages;

    public function __construct()
    {
        $this->etages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

   

    public function getNbreetage(): ?int
    {
        return $this->nbreetage;
    }

    public function setNbreetage(int $nbreetage): static
    {
        $this->nbreetage = $nbreetage;

        return $this;
    }

    /**
     * @return Collection<int, Etage>
     */
    public function getEtages(): Collection
    {
        return $this->etages;
    }

    public function addEtage(Etage $etage): static
    {
        if (!$this->etages->contains($etage)) {
            $this->etages->add($etage);
            $etage->setDepartement($this);
        }

        return $this;
    }

    public function removeEtage(Etage $etage): static
    {
        if ($this->etages->removeElement($etage)) {
            // set the owning side to null (unless already changed)
            if ($etage->getDepartement() === $this) {
                $etage->setDepartement(null);
            }
        }

        return $this;
    }
}
