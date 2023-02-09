<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $nomCategorie;

    #[ORM\Column(type: 'boolean')]
    private $commandabilite;

    #[ORM\Column(type: 'text')]
    private $catImg;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Produit::class, orphanRemoval: true)]
    private $produits;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'string', length: 100)]
    private $titre;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nomCategorie;
    }

    public function setNomCategorie(string $nomCategorie): self
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }

    public function isCommandabilite(): ?bool
    {
        return $this->commandabilite;
    }

    public function setCommandabilite(bool $commandabilite): self
    {
        $this->commandabilite = $commandabilite;

        return $this;
    }

    public function getCatImg(): ?string
    {
        return $this->catImg;
    }

    public function setCatImg(?string $catImg): self
    {
        $this->catImg = $catImg;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setCategorie($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCategorie() === $this) {
                $produit->setCategorie(null);
            }
        }

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
    
    public function getTitre(): ?string
    {
        return $this->titre;
    }
    
    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        
        return $this;
    }

    public function __toString(){
        return (string) $this->titre;
    }
}
