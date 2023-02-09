<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Service;
use App\Entity\Categorie;
use App\Form\SearchProduitType;
use App\Service\Cart\CartService;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {  
        return $this->render('produit/index.html.twig', []);
    }
    
    #[Route('/produit/{id}', name: 'show_produit')]
    public function showProduit(ManagerRegistry $doctrine, Produit $produit, CartService $cartService, Request $request, ProduitRepository $produitRepo): Response
    {
        $categories = $doctrine->getRepository(Categorie::class)->findAll();
        asort($categories);

        $services = $doctrine->getRepository(Service::class)->findAll();
        
        $quantity = $cartService->getQuantity($produit);

        // search bar
        $form = $this->createForm(SearchProduitType::class);
        $search = $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $q = $search->get('mots')->getData();
            $prodsSearched = $produitRepo->search($q);
        }else{
            $q = '';
            $prodsSearched = '';
        }
        
        return $this->render('produit/index.html.twig', [
            'produit' => $produit,
            'categories' => $categories,
            'services' => $services,
            'items' => $cartService->getFullCart(),
            'quantity' => $quantity,
            'searchForm' => $form,
            'prodsSearched' => $prodsSearched,
            'q' => $q,
        ]);
    }
}
