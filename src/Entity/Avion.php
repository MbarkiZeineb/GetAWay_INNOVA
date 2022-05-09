<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avion
 *
 * @ORM\Table(name="avion", indexes={@ORM\Index(name="id_agence", columns={"id_agence"})})
 * @ORM\Entity(repositoryClass="App\Repository\AvionRepository")
 */
class Avion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_avion", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAvion;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_place", type="integer", nullable=false)
     */
    private $nbrPlace;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_avion", type="string", length=30, nullable=false)
     */
    private $nomAvion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=50, nullable=true)
     */
    private $type;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_agence", referencedColumnName="id")
     * })
     */
    private $idAgence;

    public function getIdAvion(): ?int
    {
        return $this->idAvion;
    }

    public function getNbrPlace(): ?int
    {
        return $this->nbrPlace;
    }

    public function setNbrPlace(int $nbrPlace): self
    {
        $this->nbrPlace = $nbrPlace;

        return $this;
    }

    public function getNomAvion(): ?string
    {
        return $this->nomAvion;
    }

    public function setNomAvion(string $nomAvion): self
    {
        $this->nomAvion = $nomAvion;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getIdAgence(): ?User
    {
        return $this->idAgence;
    }

    public function setIdAgence(?User $idAgence): self
    {
        $this->idAgence = $idAgence;

        return $this;
    }


}
