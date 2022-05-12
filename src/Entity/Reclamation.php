<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="idClient", columns={"idClient"})})
 * @ORM\Entity(repositoryClass="App\Repository\ReclamationRepository")
 */
class Reclamation
{
    /**
     * @var int
     *
     * @ORM\Column(name="idR", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups ("post:read")
     */
    private $idr;

    /**
     * @var string
     *
     * @ORM\Column(name="objet", type="string", length=100, nullable=false)
     *  @Assert\NotBlank(message="vous devez remplir ce champ")
     * @Groups ("post:read")
     */
    private $objet;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=false)
     *  @Assert\NotBlank(message="vous devez remplir ce champ")
     * @Groups ("post:read")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=50, nullable=false)
     *  @Groups ("post:read")
     */
    private $etat = '0';

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User",inversedBy="reclamation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idClient", referencedColumnName="id")
     * })
     *@Groups("post:read")
     */
    private $idclient;

    public function getIdr(): ?int
    {
        return $this->idr;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getIdclient(): ?User
    {
        return $this->idclient;
    }

    public function setIdclient(?User $idclient): self
    {
        $this->idclient = $idclient;

        return $this;
    }

}
