<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hebergement
 *
 * @ORM\Table(name="hebergement", indexes={@ORM\Index(name="fk_categ", columns={"id_categ"}), @ORM\Index(name="fk_off", columns={"offreur_id"})})
 * @ORM\Entity
 */
class Hebergement
{
    /**
     * @var int
     *
     * @ORM\Column(name="referance", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $referance;

    /**
     * @var string|null
     *
     * @ORM\Column(name="paye", type="string", length=15, nullable=true)
     */
    private $paye;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adress", type="string", length=50, nullable=true)
     */
    private $adress;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=true)
     */
    private $prix;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=300, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="photo", type="string", length=999, nullable=true)
     */
    private $photo;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_start", type="date", nullable=true)
     */
    private $dateStart;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_end", type="date", nullable=true)
     */
    private $dateEnd;

    /**
     * @var int|null
     *
     * @ORM\Column(name="contact", type="integer", nullable=true)
     */
    private $contact;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_detoile", type="integer", nullable=true)
     */
    private $nbrDetoile;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_suite", type="integer", nullable=true)
     */
    private $nbrSuite;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbr_parking", type="integer", nullable=true)
     */
    private $nbrParking;

    /**
     * @var string|null
     *
     * @ORM\Column(name="model_caravane", type="string", length=15, nullable=true)
     */
    private $modelCaravane;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="offreur_id", referencedColumnName="id")
     * })
     */
    private $offreur;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categ", referencedColumnName="id_categ")
     * })
     */
    private $idCateg;

    public function getReferance(): ?int
    {
        return $this->referance;
    }

    public function getPaye(): ?string
    {
        return $this->paye;
    }

    public function setPaye(?string $paye): self
    {
        $this->paye = $paye;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(?\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getContact(): ?int
    {
        return $this->contact;
    }

    public function setContact(?int $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getNbrDetoile(): ?int
    {
        return $this->nbrDetoile;
    }

    public function setNbrDetoile(?int $nbrDetoile): self
    {
        $this->nbrDetoile = $nbrDetoile;

        return $this;
    }

    public function getNbrSuite(): ?int
    {
        return $this->nbrSuite;
    }

    public function setNbrSuite(?int $nbrSuite): self
    {
        $this->nbrSuite = $nbrSuite;

        return $this;
    }

    public function getNbrParking(): ?int
    {
        return $this->nbrParking;
    }

    public function setNbrParking(?int $nbrParking): self
    {
        $this->nbrParking = $nbrParking;

        return $this;
    }

    public function getModelCaravane(): ?string
    {
        return $this->modelCaravane;
    }

    public function setModelCaravane(?string $modelCaravane): self
    {
        $this->modelCaravane = $modelCaravane;

        return $this;
    }

    public function getOffreur(): ?User
    {
        return $this->offreur;
    }

    public function setOffreur(?User $offreur): self
    {
        $this->offreur = $offreur;

        return $this;
    }

    public function getIdCateg(): ?Category
    {
        return $this->idCateg;
    }

    public function setIdCateg(?Category $idCateg): self
    {
        $this->idCateg = $idCateg;

        return $this;
    }


}
