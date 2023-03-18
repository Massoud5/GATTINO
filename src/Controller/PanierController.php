<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Service\Cart\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    // Dans le panier

    #[Route('/panier', name: 'index_panier')]
    public function index(CartService $cart): Response
    {

        return $this->render('nav_bar/panier.html.twig', [
            'items' => $cart->getFullCart()
        ]);
    }

    #[Route('/panier/ajouter/produit/{produit}', name: 'panier_ajouter')]
    public function ajouter(Produit $produit, CartService $cartService)
    {
        // si le produit appartient à une catégorie
        if ($produit->getCategorie()) {
            // la quantité d'ajout de produit d'une catégorie est limité à 20
            $cartService->addIncrement($produit, 20);
        } else {
            // la quantité d'ajout de produit d'un service est limité à 12
            $cartService->addIncrement($produit, 12);
        }

        return $this->redirectToRoute("index_panier");
    }

    #[Route('/panier/reduire/produit/{produit}', name: 'panier_reduire')]
    public function reduire(Produit $produit, CartService $cartService): Response
    {
        $cartService->decrement($produit);

        return $this->redirectToRoute("index_panier");
    }

    #[Route('/panier/supprimer/produit/{produit}', name: 'panier_supprimer')]
    public function supprimer(Produit $produit, CartService $cartService): Response
    {
        $cartService->delete($produit);

        return $this->redirectToRoute("index_panier");
    }

    #[Route('/panier/vider', name: 'panier_vider')]
    public function vider(CartService $cartService): Response
    {
        $cartService->deleteAll();

        return $this->redirectToRoute("index_panier");
    }
    // --------------------------------
    // Dans Categorie
    #[Route('/categorie/ajouter/produit/{produit}', name: 'panier_ajouterC')]
    public function ajouterDansCat(Produit $produit, CartService $cartService)
    {
        // limiter la quantité d'ajout de produit dans le panier
        if ($produit->getCategorie()) {
            $cartService->addIncrement($produit, 20);
        } else {
            $cartService->addIncrement($produit, 12);
        }

        // content reload with ajax
        $quantity = $cartService->getQuantity($produit);
        // dd($quantTotal);

        if ($cartService->getFullCart()) {
            $quantTotal = $cartService->getFullCart()['data']['quantityCart'];
            return $this->json(['quantity' => $quantity, 'produitTitre' => $produit->getTitre(), 'totalPanier' => $quantTotal], 200);
        } else {
            return $this->json(['quantity' => $quantity, 'produitTitre' => $produit->getTitre(), 'totalPanier' => 0], 200);
        }

        // return $this->redirectToRoute("show_categorie", [
        //     'id' => $produit->getCategorie()->getId(),
        // ]);
    }

    #[Route('/categorie/reduire/produit/{produit}', name: 'panier_reduireC')]
    public function reduireDansCat(Produit $produit, CartService $cartService): Response
    {
        $cartService->decrement($produit);

        // content reload with ajax
        $quantity = $cartService->getQuantity($produit);

        if ($cartService->getFullCart()) {
            $quantTotal = $cartService->getFullCart()['data']['quantityCart'];
            return $this->json(['quantity' => $quantity, 'produitTitre' => $produit->getTitre(), 'totalPanier' => $quantTotal], 200);
        } else {
            return $this->json(['quantity' => $quantity, 'produitTitre' => $produit->getTitre(), 'totalPanier' => 0], 200);
        }

        // return $this->redirectToRoute("show_categorie", [
        //     'id' => $produit->getCategorie()->getId()
        // ]);
    }
    // --------------------------------
    // Dans Produit
    #[Route('/produit/ajouter/{produit}', name: 'panier_ajouterP')]
    public function ajouterDansProd(Produit $produit, CartService $cartService)
    {
        // limiter la quantité d'ajout de produit dans le panier
        if ($produit->getCategorie()) {
            $cartService->addIncrement($produit, 20);
        } else {
            $cartService->addIncrement($produit, 12);
        }

        return $this->redirectToRoute("show_produit", [
            'id' => $produit->getId()
        ]);
    }

    #[Route('/produit/reduire/{produit}', name: 'panier_reduireP')]
    public function reduireDansProd(Produit $produit, CartService $cartService): Response
    {
        $cartService->decrement($produit);
        return $this->redirectToRoute("show_produit", [
            'id' => $produit->getId()
        ]);
    }
    // --------------------------------
}
