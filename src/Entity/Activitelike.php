<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Activitelike
 *
 * @ORM\Table(name="activitelike", indexes={@ORM\Index(name="fk_user", columns={"User"}), @ORM\Index(name="fk_actt", columns={"Act"})})
 * @ORM\Entity(repositoryClass="App\Repository\ActivitelikeRepository")
 */

class Activitelike
{

    /**
     * @var int
     *
     * @ORM\Column(name="IdLike", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idlike;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="likes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="User", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Activite
     *
     * @ORM\ManyToOne(targetEntity="Activite", inversedBy="likes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Act", referencedColumnName="RefAct")
     * })
     */
    private $act;

    public function getIdlike(): ?int
    {
        return $this->idlike;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAct(): ?Activite
    {
        return $this->act;
    }

    public function setAct(?Activite $act): self
    {
        $this->act = $act;

        return $this;
    }


}