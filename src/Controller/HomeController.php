<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Categorie;
use App\Service\Cart\CartService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\SearchProduitType;
use App\Repository\ProduitRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine, CartService $cartService, Request $request, ProduitRepository $produitRepo): Response
    {
        $categories = $doctrine->getRepository(Categorie::class)->findAll();
        asort($categories);

        $services = $doctrine->getRepository(Service::class)->findAll();

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

        return $this->render('home/indexHome.html.twig', [
            'categories' => $categories,
            'services' => $services,
            'items' => $cartService->getFullCart(),
            'searchForm' => $form,
            'prodsSearched' => $prodsSearched,
            'q' => $q,
            'isHome' =>  true,
        ]);
    }

    #[Route('/show/assortiments-cadeaux', name: 'show_asort_cadeaux')]
    public function showInfoCommande(ManagerRegistry $doctrine): Response
    {
        $categories = $doctrine->getRepository(Categorie::class)->findAll();
        return $this->render('home/assort_cadeaux.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/show/1j1p', name: 'show_1j1p')]
    public function show_1j1p(ManagerRegistry $doctrine): Response
    {
        $categories = $doctrine->getRepository(Categorie::class)->findAll();
        return $this->render('home/1j1p.html.twig', [
            'categories' => $categories,
        ]);
    }
}