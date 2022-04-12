<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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


}
