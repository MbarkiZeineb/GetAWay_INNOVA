<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Activite
 *
 * @ORM\Table(name="activite")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ActiviteRepository")
 * @Vich\Uploadable
 */

class Activite
{
    /**
     * @var int
     *
     * @ORM\Column(name="RefAct", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $refact;

    /**
     * @var string
     * @Assert\NotBlank(message="Nom ne doit pas être vide")
     * @ORM\Column(name="Nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var string
     * @Assert\NotBlank(message="Description ne doit pas être vide")
     * @ORM\Column(name="Descrip", type="string", length=50, nullable=false)
     */
    private $descrip;

    /**
     * @var string
     *  @Assert\NotBlank(message="Durée ne doit pas être vide")
     * @ORM\Column(name="Duree", type="string", length=50, nullable=false)
     */
    private $duree;

    /**
     * @var int
     * @Assert\NotBlank(message="Nombre de place ne doit pas être vide")
     * @Assert\GreaterThanOrEqual(1,message="Nombre de place doix être >= 1")
     *
     * @ORM\Column(name="NbrPlace", type="integer", nullable=false)
     */
    private $nbrplace;

    /**
     * @var string
     *
     * @Assert\NotNull()
     * @ORM\Column(name="date", type="datetime", nullable=false)
     * @Assert\GreaterThan("today",message="Doix etre >= à la date d'aujourd'hui")
     *
     */
    private $date;

    /**
     * @var string
     * @Assert\NotBlank(message="Type ne doit pas être vide")
     *
     * @ORM\Column(name="Type", type="string", length=50, nullable=false)
     */
    private $type;

    /**
     * @var string
     * @Assert\NotBlank(message="Localisation ne doit pas être vide")
     *
     * @ORM\Column(name="Location", type="string", length=50, nullable=false)
     */
    private $location;

    /**
     * @var float
     * @Assert\NotBlank(message="Prix ne doit pas être vide")
     * @Assert\Range(
     *      min = 10.0,
     *      max = 200.0)
     * @Assert\Type("float")
     *
     * @ORM\Column(name="Prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="activite_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @var int
     * @ORM\OneToMany(targetEntity="App\Entity\Activitelike", mappedBy="act")
     */
    private $likes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    public function getRefact(): ?int
    {
        return $this->refact;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescrip(): ?string
    {
        return $this->descrip;
    }

    public function setDescrip(string $descrip): self
    {
        $this->descrip = $descrip;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getNbrplace(): ?int
    {
        return $this->nbrplace;
    }

    public function setNbrplace(int $nbrplace): self
    {
        $this->nbrplace = $nbrplace;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }


    /**
     * @return Collection|Activitelike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    /**
     *
     * @return boolean
     */
    public function isLikedByUser(User $user): bool
    {

        foreach ($this->likes as $like) {
            if ($like->getUser() === $user) {
                return true;
            }
        }

        return false;
    }

    public function addLike(Activitelike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setAct($this);
        }

        return $this;
    }

    public function removeLike(Activitelike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getAct() === $this) {
                $like->setAct(null);
            }
        }

        return $this;
    }

}