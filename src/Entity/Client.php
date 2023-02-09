<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    // #[Assert\NotBlank(message:"Veuillez renseigner ce champ")]
    #[ORM\Column(type: 'string', length: 1)]
    private $civilite;

    #[Assert\NotBlank(message:"Veuillez renseigner ce champ")]
    #[Assert\Length(min:4, minMessage:"Veuillez avoir au moins 4 caractères.")]
    #[ORM\Column(type: 'string', length: 50)]
    private $prenom;

    #[ORM\Column(type: 'string', length: 50)]
    private $nom;

    #[ORM\Column(type: 'string', length: 100)]
    private $email;

    // #[Assert\Length(min:10, minMessage:"Veuillez mettre 10 chiffres commençant par 0.")]
    // #[Assert\Length(max:10, maxMessage:"Veuillez mettre 10 chiffres commençant par 0.")]
    #[ORM\Column(type: 'string', length: 10)]
    private $tel;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $raisonSociale;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private $cp;

    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    private $ville;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $adresse;

    #[ORM\Column(type: 'date', nullable: true)]
    private $dateRetrait;

    #[ORM\Column(type: 'string', nullable: true)]
    private $tempsRetrait;
    
    #[ORM\Column(type: 'date', nullable: true)]
    private $dateRetraitPA;

    #[ORM\Column(type: 'string', nullable: true)]
    private $tempsRetraitPA;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): self
    {
        $this->civilite = $civilite;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getRaisonSociale(): ?string
    {
        return $this->raisonSociale;
    }

    public function setRaisonSociale(?string $raisonSociale): self
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(?string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

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

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->dateRetrait;
    }

    public function setDateRetrait(\DateTimeInterface $dateRetrait): self
    {
        $this->dateRetrait = $dateRetrait;

        return $this;
    }

    public function getTempsRetrait(): ?string
    {
        return $this->tempsRetrait;
    }

    public function setTempsRetrait(?string $tempsRetrait): self
    {
        $this->tempsRetrait = $tempsRetrait;

        return $this;
    }

    public function getDateRetraitPA(): ?\DateTimeInterface
    {
        return $this->dateRetraitPA;
    }

    public function setDateRetraitPA(?\DateTimeInterface $dateRetraitPA): self
    {
        $this->dateRetraitPA = $dateRetraitPA;

        return $this;
    }

    public function getTempsRetraitPA(): ?string
    {
        return $this->tempsRetraitPA;
    }

    public function setTempsRetraitPA(?string $tempsRetraitPA): self
    {
        $this->tempsRetraitPA = $tempsRetraitPA;

        return $this;
    }
}
