<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Avis
 *
 * @ORM\Table(name="avis", indexes={@ORM\Index(name="fk_idavis", columns={"Id"}), @ORM\Index(name="frk_act", columns={"RefActivite"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\AvisRepository")
 */
class Avis
{
    /**
     * @var int
     *
     * @ORM\Column(name="RefAvis", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $refavis;

    /**
     * @var string
     * @Assert\NotBlank(message="Message ne doit pas être vide")
     * @ORM\Column(name="Message", type="string", length=250, nullable=false)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var int
     * @Assert\NotBlank(message="Rating ne doit pas être vide")
     * @Assert\Positive(message="Le rating ne dois pas etre négatif")
     * @Assert\Range(
     *      min = 1,
     *      max = 5)
     * @ORM\Column(name="Rating", type="integer", nullable=false)
     */
    private $rating;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id", referencedColumnName="id")
     * })
     */
    private $id;

    /**
     * @var \Activite
     *
     * @ORM\ManyToOne(targetEntity="Activite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="RefActivite", referencedColumnName="RefAct")
     * })
     */
    private $refactivite;

    public function getRefavis(): ?int
    {
        return $this->refavis;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getId(): ?User
    {
        return $this->id;
    }

    public function setId(?User $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getRefactivite(): ?Activite
    {
        return $this->refactivite;
    }

    public function setRefactivite(?Activite $refactivite): self
    {
        $this->refactivite = $refactivite;

        return $this;
    }

}