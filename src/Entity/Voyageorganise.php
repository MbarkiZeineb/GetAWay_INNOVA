<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Voyageorganise
 *
 * @ORM\Table(name="voyageorganise", indexes={@ORM\Index(name="idCat", columns={"idCat"})})
 * @ORM\Entity(repositoryClass="App\Repository\VoyOrgRepository")
 */
class Voyageorganise
{
    /**
     * @var int
     *
     * @ORM\Column(name="idVoy", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idvoy;

    /**
     * @var string
     *
     * @ORM\Column(name="villeDepart", type="string", length=30, nullable=false)
     */
    private $villedepart;

    /**
     * @var string
     *
     * @ORM\Column(name="villeDest", type="string", length=30, nullable=false)
     */
    private $villedest;

    /**
     * @var string
     *
     * @ORM\Column(name="dateDepart", type="string", length=20, nullable=false)
     */
    private $datedepart;

    /**
     * @var string
     *
     * @ORM\Column(name="dateArrive", type="string", length=20, nullable=false)
     */
    private $datearrive;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrPlace", type="integer", nullable=false)
     */
    private $nbrplace;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=false)
     */
    private $description;

    /**
     * @var \Categorievoy
     *
     * @ORM\ManyToOne(targetEntity="Categorievoy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCat", referencedColumnName="idcat")
     * })
     */
    private $idcat;

    public function getIdvoy(): ?int
    {
        return $this->idvoy;
    }

    public function getVilledepart(): ?string
    {
        return $this->villedepart;
    }

    public function setVilledepart(string $villedepart): self
    {
        $this->villedepart = $villedepart;

        return $this;
    }

    public function getVilledest(): ?string
    {
        return $this->villedest;
    }

    public function setVilledest(string $villedest): self
    {
        $this->villedest = $villedest;

        return $this;
    }

    public function getDatedepart(): ?string
    {
        return $this->datedepart;
    }

    public function setDatedepart(string $datedepart): self
    {
        $this->datedepart = $datedepart;

        return $this;
    }

    public function getDatearrive(): ?string
    {
        return $this->datearrive;
    }

    public function setDatearrive(string $datearrive): self
    {
        $this->datearrive = $datearrive;

        return $this;
    }

    public function getNbrplace(): ?int
    {
        return $this->nbrplace;
    }

    public function setNbrplace(int $nbrplace): self
    {
        $this->nbrplace = $nbrplace;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIdcat(): ?Categorievoy
    {
        return $this->idcat;
    }

    public function setIdcat(?Categorievoy $idcat): self
    {
        $this->idcat = $idcat;

        return $this;
    }


}
