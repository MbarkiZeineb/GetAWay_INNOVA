<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Promostion
 *
 * @ORM\Table(name="promostion", indexes={@ORM\Index(name="fk_refH", columns={"ref_hebergement"})})
 * @ORM\Entity
 */
class Promostion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_ref", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRef;

    /**
     * @var int
     *@Assert\Length(
     * min = 1,
     * max = 2,
     *   minMessage = "The Pourcentage must be at least 1%",
     *   maxMessage = "The Pourcentage cannot be more thant 99%" )
     * @ORM\Column(name="pourcentage", type="integer", nullable=false)
     */
    private $pourcentage;

    /**
     * @var \DateTime
    * @Assert\NotBlank
     * @ORM\Column(name="date_start", type="date", nullable=false)
     */
    private $dateStart;

    /**
     * @var \DateTime
     *@Assert\NotBlank
     * @Assert\GreaterThan(propertyPath="dateStart")
     * @ORM\Column(name="date_end", type="date", nullable=false)
     */
    private $dateEnd;

    /**
     * @var \Hebergement
     *@Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="Hebergement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ref_hebergement", referencedColumnName="referance")
     * })
     */
    private $refHebergement;

    public function getIdRef(): ?int
    {
        return $this->idRef;
    }

    public function getPourcentage(): ?int
    {
        return $this->pourcentage;
    }

    public function setPourcentage(int $pourcentage): self
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getRefHebergement(): ?Hebergement
    {
        return $this->refHebergement;
    }

    public function setRefHebergement(?Hebergement $refHebergement): self
    {
        $this->refHebergement = $refHebergement;

        return $this;
    }


}
