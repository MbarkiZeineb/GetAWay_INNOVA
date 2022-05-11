<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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


}
