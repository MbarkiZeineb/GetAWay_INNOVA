<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_categ", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCateg;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom_categ", type="string", length=15, nullable=true)
     * @Assert\Length(
     * min = 3,
     * max = 50,
     *   minMessage = "Name Category must be at least {{ limit }} characters long",
     *   maxMessage = "Name Category cannot be longer than {{ limit }} characters" )
     *
     */
    private $nomCateg;

    public function getIdCateg(): ?int
    {
        return $this->idCateg;
    }

    public function getNomCateg(): ?string
    {
        return $this->nomCateg;
    }

    public function setNomCateg(?string $nomCateg): self
    {
        $this->nomCateg = $nomCateg;

        return $this;
    }

    public function __toString() {
        return $this->nomCateg;
    }

}
