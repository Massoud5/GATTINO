<?php

namespace App\Controller;

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

class CategorieController extends AbstractController
{

    #[Route('/categorie/{id}', name: 'show_categorie')]
    public function showCategory(ManagerRegistry $doctrine, Categorie $categorie, CartService $cartService, Request $request, ProduitRepository $produitRepo): Response
    {
        $categories = $doctrine->getRepository(Categorie::class)->findAll();
        asort($categories);
        $services = $doctrine->getRepository(Service::class)->findAll();

        $result = $cartService->getQuantityDansCat($categorie);
        // search bar
        $form = $this->createForm(SearchProduitType::class);
        $search = $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $q = $search->get('mots')->getData();
            $prodsSearched = $produitRepo->search($q);
        } else {
            $q = '';
            $prodsSearched = '';
        }

        return $this->render('categorie/showCategorie.html.twig', [
            'categories' => $categories,
            'services' => $services,
            'categorie' => $categorie,
            'items' => $cartService->getFullCart(),
            'result' => $result,
            'searchForm' => $form,
            'prodsSearched' => $prodsSearched,
            'q' => $q,
        ]);
    }
}
