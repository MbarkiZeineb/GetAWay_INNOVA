<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Paiement
 *
 * @ORM\Table(name="paiement", indexes={@ORM\Index(name="fk_reservation", columns={"id_reservation"})})
 * @ORM\Entity(repositoryClass="App\Repository\PaiementRepository")
 */
class Paiement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="modalite_paiement", type="string", length=30, nullable=false)
     */
    private $modalitePaiement;

    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="float", precision=10, scale=0, nullable=false)
     */
    private $montant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var \Reservation
     *
     * @ORM\ManyToOne(targetEntity="Reservation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_reservation", referencedColumnName="id")
     * })
     */
    private $idReservation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModalitePaiement(): ?string
    {
        return $this->modalitePaiement;
    }

    public function setModalitePaiement(string $modalitePaiement): self
    {
        $this->modalitePaiement = $modalitePaiement;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

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

    public function getIdReservation(): ?Reservation
    {
        return $this->idReservation;
    }

    public function setIdReservation(?Reservation $idReservation): self
    {
        $this->idReservation = $idReservation;

        return $this;
    }


}
