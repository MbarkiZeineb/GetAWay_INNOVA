<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\categVoyRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
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
     * @Groups("voyage")
     */
    private $idvoy;

    /**
     * @var string
     * @Assert\NotBlank(message="le champ est vide")
     * @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "[a-zA-Z]+"
     * )
     * @Groups("voyage")
     * @ORM\Column(name="villeDepart", type="string", length=30, nullable=false)
     */
    private $villedepart;

    /**
     * @var string
     * @Assert\NotBlank(message="le champ est vide")
     * @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "[a-zA-Z]+"
     * )
     * @Groups("voyage")
     * @ORM\Column(name="villeDest", type="string", length=30, nullable=false)
     */
    private $villedest;

    /**
     * @var string
     * @Assert\NotBlank(message="le champ est vide")
     *  @Assert\GreaterThan("today")
     * @Groups("voyage")
     * @ORM\Column(name="dateDepart", type="datetime", length=20, nullable=false)
     */
    private $datedepart;

    /**
     * @var string
     * @Assert\NotBlank(message="le champ est vide")
     * @Assert\Expression(
     *     "this.getDatedepart() < this.getDatearrive()",
     *     message="La date arrive ne doit pas être inférieure à la date début"
     * )
     * @Groups("voyage")
     * @ORM\Column(name="dateArrive", type="datetime", length=20, nullable=false)
     */
    private $datearrive;

    /**
     * @var int
     * @Assert\NotBlank(message="le champ est vide")
     * @Assert\GreaterThan(5,message="nombre de place doit etre superieur a 5")
     * @Groups("voyage")
     * @ORM\Column(name="nbrPlace", type="integer", nullable=false)
     */
    private $nbrplace;

    /**
     * @var float
     * @Assert\NotBlank(message="le champ est vide")
     * @Assert\GreaterThan(0,message="prix doix être positif")
     * @Groups("voyage")
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var string
     * @Assert\NotBlank(message="le champ est vide")
     * @Groups("voyage")
     * @ORM\Column(name="description", type="string", length=1000, nullable=false)
     */
    private $description;

    /**
     * @var \Categorievoy
     *
     * @ORM\ManyToOne(targetEntity="Categorievoy")
     * @Assert\NotBlank(message="le champ est vide")
     * @Groups("voyage")
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

    public function getDatedepart(): ?\DateTimeInterface
    {
        return $this->datedepart;
    }

    public function setDatedepart(\DateTimeInterface $datedepart): self
    {
        $this->datedepart = $datedepart;

        return $this;
    }

    public function getDatearrive(): ?\DateTimeInterface
    {
        return $this->datearrive;
    }

    public function setDatearrive(\DateTimeInterface $datearrive): self
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