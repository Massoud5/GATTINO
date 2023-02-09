<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    private $dateCommande;
 
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $remise;
    
    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeProduit::class, orphanRemoval: true)]
    private $commandeProduits;
    
    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private $client;
    
    #[ORM\Column(type: 'text')]
    private $stripeSessionId;
    
    #[ORM\Column(type: 'boolean')]
    private $isPaid;

    #[ORM\Column(type: 'date', nullable: true)]
    private $dateRetrait;

    #[ORM\Column(type: 'string', nullable: true)]
    private $tempsRetrait;

    #[ORM\Column(type: 'date', nullable: true)]
    private $dateRetraitPA;

    #[ORM\Column(type: 'string', nullable: true)]
    private $tempsRetraitPA;

    #[ORM\Column(type: 'string')]
    private $ht;

    #[ORM\Column(type: 'string')]
    private $tva;

    #[ORM\Column(type: 'string', length: 255)]
    private $ttc;

    #[ORM\Column(type: 'integer', length: 255, nullable: true)]
    private $nCommandePaid;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isRetrieved;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $retrievedMoment;

    public function __construct()
    {
        $this->commandeProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function isRemise(): ?bool
    {
        return $this->remise;
    }

    public function setRemise(?bool $remise): self
    {
        $this->remise = $remise;

        return $this;
    }

    /**
     * @return Collection<int, CommandeProduit>
     */
    public function getCommandeProduits(): Collection
    {
        return $this->commandeProduits;
    }

    public function addCommandeProduit(CommandeProduit $commandeProduit): self
    {
        if (!$this->commandeProduits->contains($commandeProduit)) {
            $this->commandeProduits[] = $commandeProduit;
            $commandeProduit->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeProduit(CommandeProduit $commandeProduit): self
    {
        if ($this->commandeProduits->removeElement($commandeProduit)) {
            // set the owning side to null (unless already changed)
            if ($commandeProduit->getCommande() === $this) {
                $commandeProduit->setCommande(null);
            }
        }

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getStripeSessionId(): ?string
    {
        return $this->stripeSessionId;
    }

    public function setStripeSessionId(string $stripeSessionId): self
    {
        $this->stripeSessionId = $stripeSessionId;

        return $this;
    }
    
    public function isIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid): self
    {
        $this->isPaid = $isPaid;
        
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

    public function getHt(): ?string
    {
        return $this->ht;
    }

    public function setHt(string $ht): self
    {
        $this->ht = $ht;

        return $this;
    }

    public function getTva(): ?string
    {
        return $this->tva;
    }

    public function setTva(string $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function getTtc(): ?string
    {
        return $this->ttc;
    }

    public function setTtc(string $ttc): self
    {
        $this->ttc = $ttc;

        return $this;
    }

    public function getNCommandePaid(): ?int
    {
        return $this->nCommandePaid;
    }

    public function setNCommandePaid(?int $nCommandePaid): self
    {
        $this->nCommandePaid = $nCommandePaid;

        return $this;
    }

    public function isIsRetrieved(): ?bool
    {
        return $this->isRetrieved;
    }

    public function setIsRetrieved(?bool $isRetrieved): self
    {
        $this->isRetrieved = $isRetrieved;

        return $this;
    }

    public function getRetrievedMoment(): ?string
    {
        return $this->retrievedMoment;
    }

    public function setRetrievedMoment(?string $retrievedMoment): self
    {
        $this->retrievedMoment = $retrievedMoment;

        return $this;
    }
}
