<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *  fields = {"email"},
 *     message ="L'email que vous avez indiqué est deja utilisé! "
 * )
 */
class User implements UserInterface
{

    /**
     * @ORM\OneToMany(targetEntity=Reclamation::class, mappedBy="idclient", orphanRemoval=true)
     */
    private $reclamation;

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
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     *  @Assert\NotBlank(message="vous devez remplir ce champ")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     *  @Assert\NotBlank(message="vous devez remplir ce champ")
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     *  @Assert\NotBlank(message="vous devez remplir ce champ")
     * @Assert\Length(
     *      min = 4,
     *      max = 255,
     *      minMessage = "votre mdp doit contenir au moins {{ limit }} caracteres",
     *      maxMessage = "votre mdp doit contenir au plus {{ limit }} caracteres"
     * )
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="securityQ", type="string", length=255, nullable=true)
     */
    private $securityq;

    /**
     * @var string|null
     *
     * @ORM\Column(name="answer", type="string", length=255, nullable=true)
     *  @Assert\NotBlank(message="vous devez remplir ce champ")
     */
    private $answer;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     *  @Assert\NotBlank(message="vous devez remplir ce champ")
     * @Assert\Email(message = "Adresse email non valide ")
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     *  @Assert\NotBlank(message="vous devez remplir ce champ")
     */
    private $adresse;

    /**
     * @var int|null
     *
     * @ORM\Column(name="numtel", type="integer", nullable=true)
     *  @Assert\NotBlank(message="vous devez remplir ce champ")
     *   * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "votre mdp ne doit pas depasser {{ limit }} chiffres",
     *      maxMessage = "votre mdp ne doit pas depasser {{ limit }} chiffres"
     * )
     */
    private $numtel;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nomAgence", type="string", length=255, nullable=true)
     */
    private $nomagence;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=false)
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=50, nullable=false, options={"default"="1"})
     */
    private $etat = '1';

    /**
     * @var float|null
     *
     * @ORM\Column(name="solde", type="float", precision=10, scale=0, nullable=true)
     *  @Assert\NotBlank(message="vous devez remplir ce champ")
     * * @Assert\Positive(message="cette valeur doit etre positif")
     */
    private $solde;

    /**
     * @var string|null
     *
     * @ORM\Column(name="reset_token", type="string", length=255, nullable=true)
     */
    private $resetToken;
    private $captchaCode;
    /**
     * @var int|null
     *
     * @ORM\Column(name="likes", type="integer", nullable=true)
     */
    private $likes;
    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }
    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode=$captchaCode;
    }
    public function __construct()
    {
        $this->reclamation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSecurityq(): ?string
    {
        return $this->securityq;
    }

    public function setSecurityq(?string $securityq): self
    {
        $this->securityq = $securityq;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(?int $numtel): self
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getNomagence(): ?string
    {
        return $this->nomagence;
    }

    public function setNomagence(?string $nomagence): self
    {
        $this->nomagence = $nomagence;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

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

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(?float $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }
    public function eraseCredentials()
    {
        return $this->getEtat();
    }
    public function getSalt()
    {
        return $this->getRole();
    }
    public function getRoles()
    {
        return ['Admin','Client','Agent-Aerien','Offreur'];
    }
    public function getUsername()
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamation(): Collection
    {
        return $this->reclamation;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->reclamation->contains($reclamation)) {
            $this->reclamation[] = $reclamation;
            $reclamation->setIdclient($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->reclamation->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getIdclient() === $this) {
                $reclamation->setIdclient(null);
            }
        }

        return $this;
    }
    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(?int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

}