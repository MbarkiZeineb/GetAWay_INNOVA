<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

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
     *
     */
    private $idAvion;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_place", type="integer", nullable=false)
     * @Assert\NotBlank
     * @Assert\Positive
     * @Assert\Range(
     *      min = 1,
     *      max = 500,
     *      notInRangeMessage = " Le nombre de place doit etre entre {{ min }} et {{ max }}")
     *
     */
    private $nbrPlace;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_avion", type="string", length=30, nullable=false)
     *    @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "[a-zA-Z]+"
     * )
     *  @Assert\NotBlank
     */
    private $nomAvion;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_agence", referencedColumnName="id")
     * })
     */
    private $idAgence;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vol", mappedBy="idAvion", orphanRemoval=true)
     */
    private $vols;

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