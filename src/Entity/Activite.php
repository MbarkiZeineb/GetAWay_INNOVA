<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Activite
 *
 * @ORM\Table(name="activite")
 * @ORM\Entity
 */

class Activite
{
    /**
     * @var int
     *
     * @ORM\Column(name="RefAct", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $refact;

    /**
     * @var string
     * @Assert\NotBlank(message="Nom ne doit pas être vide")
     * @ORM\Column(name="Nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var string
     * @Assert\NotBlank(message="Description ne doit pas être vide")
     * @ORM\Column(name="Descrip", type="string", length=50, nullable=false)
     */
    private $descrip;

    /**
     * @var string
     *  @Assert\NotBlank(message="Durée ne doit pas être vide")
     * @ORM\Column(name="Duree", type="string", length=50, nullable=false)
     */
    private $duree;

    /**
     * @var int
     * @Assert\NotBlank(message="Nombre de place ne doit pas être vide")
     * @Assert\GreaterThanOrEqual(1,message="Nombre de place doix être >= 1")
     *
     * @ORM\Column(name="NbrPlace", type="integer", nullable=false)
     */
    private $nbrplace;

    /**
     * @var string
     *
     * @Assert\NotNull()
     * @ORM\Column(name="date", type="datetime", nullable=false)
     * @Assert\GreaterThan("today",message="Doix etre >= à la date d'aujourd'hui")
     *
     */
    private $date;

    /**
     * @var string
     * @Assert\NotBlank(message="Type ne doit pas être vide")
     *
     * @ORM\Column(name="Type", type="string", length=50, nullable=false)
     */
    private $type;

    /**
     * @var string
     * @Assert\NotBlank(message="Localisation ne doit pas être vide")
     *
     * @ORM\Column(name="Location", type="string", length=50, nullable=false)
     */
    private $location;

    /**
     * @var float
     * @Assert\NotBlank(message="Prix ne doit pas être vide")
     * @Assert\Positive(message="Le prix est négatif")
     * @Assert\Type("float")
     *
     * @ORM\Column(name="Prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    public function getRefact(): ?int
    {
        return $this->refact;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescrip(): ?string
    {
        return $this->descrip;
    }

    public function setDescrip(string $descrip): self
    {
        $this->descrip = $descrip;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): self
    {
        $this->duree = $duree;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

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
