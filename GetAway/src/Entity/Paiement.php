<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
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
     * @Groups("paiement")
     *
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(
     * message = " vous devez selectionner le mode de paiement",groups={"p"})
     * @ORM\Column(name="modalite_paiement", type="string", length=30, nullable=false)
     *  @Groups("paiement")
     *
     */
    private $modalitePaiement;

    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="float", precision=10, scale=0, nullable=false)
     *  @Groups("paiement")
     *
     */
    private $montant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     *  @Groups("paiement")
     *
     */
    private $date;

    /**
     * @var \Reservation
     *
     * @ORM\ManyToOne(targetEntity="Reservation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_reservation", referencedColumnName="id")
     * })
     *  @Groups("paiement")
     *
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
