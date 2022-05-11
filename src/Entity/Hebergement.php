<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hebergement
 *
 * @ORM\Table(name="hebergement", indexes={@ORM\Index(name="fk_off", columns={"offreur_id"}), @ORM\Index(name="fk_categ", columns={"id_categ"})})
 *  @ORM\Entity(repositoryClass="App\Repository\HebergementRepository")
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
     * @Assert\Length(
     * min = 3,
     * max = 50,
     *   minMessage = "Your Adress must be at least {{ limit }} characters long",
     *   maxMessage = "Your Adress cannot be longer than {{ limit }} characters" )
     */
    private $adress;

    /**
     * @var float|null
     * @Assert\NotBlank
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=true)
     */
    private $prix;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=300, nullable=true)
     *@Assert\Length(
     * max = 500,
     *
     *   maxMessage = "The description cannot be longer than {{ limit }} characters" )
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
     * @Assert\NotBlank
     * @ORM\Column(name="date_start", type="date", nullable=true)
     */
    private $dateStart;

    /**
     * @var \DateTime|null
     *@Assert\NotBlank
     * @Assert\GreaterThan(propertyPath="dateStart")
     * @ORM\Column(name="date_end", type="date", nullable=true)
     */
    private $dateEnd;

    /**
     * @var int|null
     * @Assert\Type(
     *     type="Integer"
     * )
     * @Assert\Length(
     * min = 8,
     * max = 80,
     *   minMessage = "Your Contact must be at least {{ limit }} characters long",
     *   maxMessage = "Your Contact cannot be longer than {{ limit }} characters" )
     * @ORM\Column(name="contact", type="integer", nullable=true)
     */
    private $contact;

    /**
     * @var int|null
     * @Assert\Type(
     *     type="Integer"
     * )
     * @Assert\Length(
     * min = 0,
     * max = 5,
     *   minMessage = "Nombre de etoile must be at least {{ limit }} characters long",
     *   maxMessage = "Nomber de etoile cannot be longer than {{ limit }} characters" )
     * @ORM\Column(name="nbr_detoile", type="integer", nullable=true)
     */
    private $nbrDetoile;

    /**
     * @var int|null
     * @Assert\Type(
     *     type="Integer"
     * )
     * @Assert\Length(
     * min = 0,
     * max = 100,
     *   minMessage = "Nomber de Suite emust be at least {{ limit }} characters long",
     *   maxMessage = "Nomber de Suite cannot be longer than {{ limit }} characters" )
     * @ORM\Column(name="nbr_suite", type="integer", nullable=true)
     */
    private $nbrSuite;

    /**
     * @var int|null
     * @Assert\Type(
     *     type="Integer"
     * )
     * @Assert\Length(
     * min = 0,
     * max = 5,
     *   minMessage = "Nomber de Place de parking must be at least {{ limit }} characters long",
     *   maxMessage = "Nomber de place de parking cannot be longer than {{ limit }} characters" )
     * @ORM\Column(name="nbr_parking", type="integer", nullable=true)
     */
    private $nbrParking;

    /**
     * @var string|null
     *@Assert\Length(
     * min = 2,
     * max = 20,
     *   minMessage = "Model caravane must be at least {{ limit }} characters long",
     *   maxMessage = "Mode caravane cannot be longer than {{ limit }} characters" )
     * @ORM\Column(name="model_caravane", type="string", length=15, nullable=true)
     */
    private $modelCaravane;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category",cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categ", referencedColumnName="id_categ")
     * })
     */
    private $idCateg;

    /**
     * @var \User
     * * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="offreur_id", referencedColumnName="id")
     * })
     */
    private $offreur;

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

    public function getIdCateg(): ?Category
    {
        return $this->idCateg;
    }

    public function setIdCateg(?Category $idCateg): self
    {
        $this->idCateg = $idCateg;

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

    public function __toString()
    {
        return strval($this->referance);
    }
    public function show(): string
    {
        return 'Paye :'. $this->paye .'adresse :'. $this->adress .'description :'. $this->description .'photo :'. $this->photo . ', date Start : ' . $this->dateStart->format('d/m/Y').', date end : '.$this->dateEnd->format('d/m/Y') .'contact :'. $this->contact . 'nbrDetoile :'. $this->nbrDetoile . 'nbrparking :'. $this->nbrParking. 'nbrsuite :'. $this->nbrSuite. 'nbrDetoile :'. $this->nbrDetoile . 'model caravane :'. $this->modelCaravane. 'id category :'. $this->idCateg      ;
    }
}