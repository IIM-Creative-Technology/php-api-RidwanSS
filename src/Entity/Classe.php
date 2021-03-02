<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
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
}
