<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClasseRepository::class)
 */
class Classe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nompromo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $anneefin;

    /**
     * @ORM\OneToMany(targetEntity=Etudiant::class, mappedBy="nompromo")
     */
    private $etudiants;

    /**
     * @ORM\OneToMany(targetEntity=Matiere::class, mappedBy="nompromo")
     */
    private $matieres;

    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
        $this->matieres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNompromo(): ?string
    {
        return $this->nompromo;
    }

    public function setNompromo(string $nompromo): self
    {
        $this->nompromo = $nompromo;

        return $this;
    }

    public function getAnneefin(): ?string
    {
        return $this->anneefin;
    }

    public function setAnneefin(string $anneefin): self
    {
        $this->anneefin = $anneefin;

        return $this;
    }

    /**
     * @return Collection|Etudiant[]
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants[] = $etudiant;
            $etudiant->setNompromo($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getNompromo() === $this) {
                $etudiant->setNompromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Matiere[]
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matiere $matiere): self
    {
        if (!$this->matieres->contains($matiere)) {
            $this->matieres[] = $matiere;
            $matiere->setNompromo($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): self
    {
        if ($this->matieres->removeElement($matiere)) {
            // set the owning side to null (unless already changed)
            if ($matiere->getNompromo() === $this) {
                $matiere->setNompromo(null);
            }
        }

        return $this;
    }
}
