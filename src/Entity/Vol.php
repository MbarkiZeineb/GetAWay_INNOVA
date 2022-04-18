<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vol
 *
 * @ORM\Table(name="vol", indexes={@ORM\Index(name="id_avion", columns={"id_avion"})})
 * @ORM\Entity
 */
class Vol
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_vol", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idVol;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_depart", type="datetime", nullable=false)
     */
    private $dateDepart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_arrivee", type="datetime", nullable=false)
     */
    private $dateArrivee;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_depart", type="string", length=60, nullable=false)
     */
    private $villeDepart;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_arrivee", type="string", length=50, nullable=false)
     */
    private $villeArrivee;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_placedispo", type="integer", nullable=false)
     */
    private $nbrPlacedispo;

    /**
     * @var int
     *
     * @ORM\Column(name="id_avion", type="integer", nullable=false)
     */
    private $idAvion;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    public function getIdVol(): ?int
    {
        return $this->idVol;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(\DateTimeInterface $dateDepart): self
    {
        $this->dateDepart = $dateDepart;

        return $this;
    }

    public function getDateArrivee(): ?\DateTimeInterface
    {
        return $this->dateArrivee;
    }

    public function setDateArrivee(\DateTimeInterface $dateArrivee): self
    {
        $this->dateArrivee = $dateArrivee;

        return $this;
    }

    public function getVilleDepart(): ?string
    {
        return $this->villeDepart;
    }

    public function setVilleDepart(string $villeDepart): self
    {
        $this->villeDepart = $villeDepart;

        return $this;
    }

    public function getVilleArrivee(): ?string
    {
        return $this->villeArrivee;
    }

    public function setVilleArrivee(string $villeArrivee): self
    {
        $this->villeArrivee = $villeArrivee;

        return $this;
    }

    public function getNbrPlacedispo(): ?int
    {
        return $this->nbrPlacedispo;
    }

    public function setNbrPlacedispo(int $nbrPlacedispo): self
    {
        $this->nbrPlacedispo = $nbrPlacedispo;

        return $this;
    }

    public function getIdAvion(): ?int
    {
        return $this->idAvion;
    }

    public function setIdAvion(int $idAvion): self
    {
        $this->idAvion = $idAvion;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }


}
