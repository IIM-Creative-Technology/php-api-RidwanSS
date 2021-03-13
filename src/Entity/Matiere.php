<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatiereRepository::class)
 */
class Matiere
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
    private $nomcours;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datestart;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateend;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="matieres")
     */
    private $nompromo;

    /**
     * @ORM\ManyToOne(targetEntity=Intervenant::class, inversedBy="matieres")
     */
    private $nomintervenant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomcours(): ?string
    {
        return $this->nomcours;
    }

    public function setNomcours(string $nomcours): self
    {
        $this->nomcours = $nomcours;

        return $this;
    }

    public function getDatestart(): ?\DateTimeInterface
    {
        return $this->datestart;
    }

    public function setDatestart(\DateTimeInterface $datestart): self
    {
        $this->datestart = $datestart;

        return $this;
    }

    public function getDateend(): ?\DateTimeInterface
    {
        return $this->dateend;
    }

    public function setDateend(\DateTimeInterface $dateend): self
    {
        $this->dateend = $dateend;

        return $this;
    }

    public function getNompromo(): ?Classe
    {
        return $this->nompromo;
    }

    public function setNompromo(?Classe $nompromo): self
    {
        $this->nompromo = $nompromo;

        return $this;
    }

    public function getNomintervenant(): ?Intervenant
    {
        return $this->nomintervenant;
    }

    public function setNomintervenant(?Intervenant $nomintervenant): self
    {
        $this->nomintervenant = $nomintervenant;

        return $this;
    }
}
