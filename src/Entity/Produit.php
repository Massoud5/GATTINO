<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
// pour le search bar
#[ORM\Table(name:'produit')]
#[ORM\Index(name: 'produit_idx', columns: ['titre', 'marque'], flags: ['fulltext'])]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    
    #[ORM\Column(type: 'string', length: 255)]
    private $nomProduit;
    
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $marque;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $reference;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'text')]
    private $imgProduit;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $uniteMesure;

    #[ORM\Column(type: 'float')]
    private $prixUnitaire;

    #[ORM\Column(type: 'float', nullable: true)]
    private $taxe;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $promotion;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: true)]
    private $categorie;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: CommandeProduit::class, orphanRemoval: true)]
    private $commandeProduits;

    #[ORM\ManyToOne(targetEntity: Service::class, inversedBy: 'produits')]
    private $service;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $produitAlcoolique;

    #[ORM\Column(type: 'boolean')]
    private $commandable;

    #[ORM\Column(type: 'string', length: 255)]
    private $titre;

    #[ORM\Column(type: 'float', nullable: true)]
    private $prixAuKiloLitre;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $poidsVolumeUnitaire;

    #[ORM\Column(type: 'boolean')]
    private $active;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $quantity;

    public function __construct()
    {
        $this->commandeProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->nomProduit;
    }
    
    public function setNomProduit(string $nomProduit): self
    {
        $this->nomProduit = $nomProduit;
        
        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

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

    public function getImgProduit(): ?string
    {
        return $this->imgProduit;
    }

    public function setImgProduit(string $imgProduit): self
    {
        $this->imgProduit = $imgProduit;

        return $this;
    }

    public function getUniteMesure(): ?string
    {
        return $this->uniteMesure;
    }

    public function setUniteMesure(string $uniteMesure): self
    {
        $this->uniteMesure = $uniteMesure;

        return $this;
    }

    public function getPrixUnitaire(): ?float
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(float $prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    public function getTaxe(): ?float
    {
        return $this->taxe;
    }

    public function setTaxe(float $taxe): self
    {
        $this->taxe = $taxe;

        return $this;
    }

    public function isPromotion(): ?bool
    {
        return $this->promotion;
    }

    public function setPromotion(?bool $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

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
            $commandeProduit->setProduit($this);
        }

        return $this;
    }

    public function removeCommandeProduit(CommandeProduit $commandeProduit): self
    {
        if ($this->commandeProduits->removeElement($commandeProduit)) {
            // set the owning side to null (unless already changed)
            if ($commandeProduit->getProduit() === $this) {
                $commandeProduit->setProduit(null);
            }
        }

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    
    public function isProduitAlcoolique(): ?bool
    {
        return $this->produitAlcoolique;
    }
    
    public function setProduitAlcoolique(?bool $produitAlcoolique): self
    {
        $this->produitAlcoolique = $produitAlcoolique;
        
        return $this;
    }
    
    public function isCommandable(): ?bool
    {
        return $this->commandable;
    }
    
    public function setCommandable(bool $commandable): self
    {
        $this->commandable = $commandable;
        
        return $this;
    }
    
    public function getTitre(): ?string
    {
        return $this->titre;
    }
    
    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        
        return $this;
    }
    
    public function getPrixAuKiloLitre(): ?float
    {
        return $this->prixAuKiloLitre;
    }
    
    public function setPrixAuKiloLitre(?float $prixAuKiloLitre): self
    {
        $this->prixAuKiloLitre = $prixAuKiloLitre;
        
        return $this;
    }
    
    public function getPoidsVolumeUnitaire(): ?int
    {
        return $this->poidsVolumeUnitaire;
    }
    
    public function setPoidsVolumeUnitaire(?int $poidsVolumeUnitaire): self
    {
        $this->poidsVolumeUnitaire = $poidsVolumeUnitaire;
        
        return $this;
    }
    
    public function isActive(): ?bool
    {
        return $this->active;
    }
    
    public function setActive(bool $active): self
    {
        $this->active = $active;
        
        return $this;
    }
    
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }
    
    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;
        
        return $this;
    }

    public function __toString(): string {
        // doit être nomProduit pour ne pas déformer la méthode getQuantityDansCat() dans CartService.php
        return  $this->nomProduit;
    }
}
