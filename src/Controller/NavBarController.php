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

class NavBarController extends AbstractController
{
    #[Route('/nav', name: 'app_navbar')]
    public function index(): Response
    {
        return $this->render('nav_bar/index.html.twig', []);
    }

    #[Route('/show/actualites', name: 'show_actualites')]
    public function showActualites(ManagerRegistry $doctrine): Response
    {
        $categories = $doctrine->getRepository(Categorie::class)->findBy([], []);
        $services = $doctrine->getRepository(Service::class)->findAll();
        return $this->render('nav_bar/actualites.html.twig', [
            'categories' => $categories,
            'services' => $services,
        ]);
    }
    
    #[Route('/show/notreHistoire', name: 'show_notreHistoire')]
    public function showNotreHistoire(ManagerRegistry $doctrine): Response
    {
        $categories = $doctrine->getRepository(Categorie::class)->findBy([], []);
        $services = $doctrine->getRepository(Service::class)->findAll();
        return $this->render('nav_bar/notreHistoire.html.twig', [
            'categories' => $categories,
            'services' => $services,
        ]);
    }

    #[Route('/produits/plateaux', name: 'access_plateaux')]
    public function accessPlateaux(ManagerRegistry $doctrine, CartService $cartService, Request $request, ProduitRepository $produitRepo): Response
    {
        $categories = $doctrine->getRepository(Categorie::class)->findBy([], []);
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

        return $this->render('nav_bar/plateaux.html.twig', [
            'categories' => $categories,
            'services' => $services,
            'items' => $cartService->getFullCart(),
            'searchForm' => $form->createView(),
            'prodsSearched' => $prodsSearched,
            'q' => $q,
        ]);
    }
}