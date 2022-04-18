<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Categorievoy
 *
 * @ORM\Table(name="categorievoy")
 * @ORM\Entity
 */
class Categorievoy
{
    /**
     * @var int
     *
     * @ORM\Column(name="idcat", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcat;

    /**
     * @var string
     *
     * @ORM\Column(name="nomcat", type="string", length=30, nullable=false)
     */
    private $nomcat;

    /**
     * @var int
     *@Assert\LessThan(value=100,message="capacite ne depasse pas 100")
     * @ORM\Column(name="capacitevoy", type="integer", nullable=false)
     */
    private $capacitevoy;

    /**
     * @var string
     *
     * @ORM\Column(name="exigence", type="string", length=100, nullable=false)
     */
    private $exigence;

    /**
     * @var int
     *@Assert\LessThan(
     *     value = 100, message="reduction ne peux pas 100%"
     * )
     * @ORM\Column(name="reduction", type="integer", nullable=false)
     */
    private $reduction;

    public function getIdcat(): ?int
    {
        return $this->idcat;
    }

    public function getNomcat(): ?string
    {
        return $this->nomcat;
    }

    public function setNomcat(string $nomcat): self
    {
        $this->nomcat = $nomcat;

        return $this;
    }

    public function getCapacitevoy(): ?int
    {
        return $this->capacitevoy;
    }

    public function setCapacitevoy(int $capacitevoy): self
    {
        $this->capacitevoy = $capacitevoy;

        return $this;
    }

    public function getExigence(): ?string
    {
        return $this->exigence;
    }

    public function setExigence(string $exigence): self
    {
        $this->exigence = $exigence;

        return $this;
    }

    public function getReduction(): ?int
    {
        return $this->reduction;
    }

    public function setReduction(int $reduction): self
    {
        $this->reduction = $reduction;

        return $this;
    }


}
