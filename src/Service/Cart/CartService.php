<?php

namespace App\Service\Cart;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{

    protected $session;
    protected $produitRepository;

    public function __construct(RequestStack $requestStack, ProduitRepository $produitRepository)
    {
        $this->session = $requestStack->getSession();
        $this->produitRepository = $produitRepository;
    }

    public function addIncrement(Produit $produit, int $limite)
    {
        $cart = $this->session->get('cart', []);
        $id = $produit->getId();

        // si le produit existe déjà dans le panier
        if (!empty($cart[$id])) {
            // si la limite est atteinte, ne pas ajouter
            if ($cart[$id] === $limite) {
                $cart[$id];
            } else {
                $cart[$id]++;
            }
        }else {
            $cart[$id] = 1;
        }

        // mise à jour de session
        $this->session->set('cart', $cart);
    }

    public function decrement(Produit $produit)
    {
        $cart = $this->session->get('cart');
        $id = $produit->getId();

        if (!empty($cart)) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function delete(Produit $produit)
    {
        $cart = $this->session->get('cart', []);
        $id = $produit->getId();

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }

        $this->session->set('cart', $cart);
    }

    public function deleteAll()
    {
        $cart = $this->session->get('cart');

        if (!empty($cart)) {
            $this->session->remove('cart');
        }
    }

    public function getFullCart(): array
    {
        $cart = $this->session->get('cart', []);
        $fullCart = [];
        $quantityCart = 0;
        $totalTTC = 0;
        $totalTaxe = 0;

        foreach ($cart as $id => $quantity) {
            $produit = $this->produitRepository->find($id);
            if ($produit) {
                $fullCart['produits'][] = [
                    'produit' => $produit,
                    'quantity' => $quantity
                ];

                $quantityCart += $quantity;
                $totalTTC += $produit->getPrixUnitaire() * $quantity;
                $totalTaxe += ($produit->getTaxe() * $produit->getPrixUnitaire()) * $quantity;
            } else {
                // si le produit n'existe pas supprime-le du panier
                $this->delete($id);
            }
        }
        if ($fullCart) {
            $fullCart['data'] = [
                "quantityCart" => $quantityCart,
                "totalTTC" => $totalTTC,
                "totalTaxe" => number_format($totalTaxe, 2, ","),
                "totalHT" => number_format(($totalTTC - $totalTaxe), 2, ",")
            ];
        }

        return $fullCart;
    }

    public function getQuantity(Produit $produit): int
    {

        $cart = $this->session->get('cart');
        $id = $produit->getId();

        if (isset($cart[$id])) {
            return $cart[$id];
        } else {
            return 0;
        }
    }
    public function getQuantityDansCat(Categorie $categorie)
    {

        $cart = $this->session->get('cart');
        $produits = $categorie->getProduits()->toArray();
        $activeProds = [];
        foreach ($produits as $produit) {
            if ($produit->isActive() == true) {
                $activeProds[] = $produit;
            }
        }
        // dd($produits);

        // si le panier n'est pas vide, afficher la quantité 0 pour les produits qui sont pas dans le panier 
        // mais afficher la bonne quantité pour les produits qui sont dans le panier. 
        if (!empty($cart)) {
            $produitsP = [];
            $produitsC = [];
            $result = [];

            foreach ($activeProds as $produit) {
                foreach ($cart as $id => $quantity) {
                    if ($produit->getId() === $id) {
                        $prod = $this->produitRepository->find($id);
                        // tous les produits qui sont dans le panier
                        $produitsP[] = [
                            'produit' => $prod,
                            'quantity' => $quantity
                        ];
                    }
                }
                // tous les produits actives de la catégorie
                $quantity = 0;
                $produitsC[] = [
                    'produit' => $produit,
                    'quantity' => $quantity
                ];
            }

            // le nom de tous les produits qui sont dans le panier
            $filter = array_column($produitsP, 'produit');
            $filterNoms = [];
            foreach ($filter as $key) {
                $filterNoms[] = $key->getNomProduit();
            }

            // le nom de tous les produits actives de la catégorie. Representant le produit complet.
            $resultat = array_combine(array_column($produitsC, 'produit'), $produitsC);

            // supprimer le produit dans categorie qui a le même nom que celui existant dans le panier
            array_walk($filterNoms, function ($entry) use (&$resultat) {
                unset($resultat[$entry]);
            });
            $arrayDiff = array_values($resultat);

            // remplacer le produit supprimé de la categorie par celui identique existant dans le panier(qui a une quantité plus que 0)
            $result = array_merge($arrayDiff, $produitsP);

            // afficher les produit dans l'ordre alphabétique du nom du produit
            asort($result);

            return $result;
        }
        // si le panier est vide, afficher tous les produits de categorie avec quantité de 0
        else {

            $result = [];
            foreach ($activeProds as $produit) {
                $quantity = 0;
                $result[] = [
                    'produit' => $produit,
                    'quantity' => $quantity
                ];
            }

            return $result;
        }
    }
}
