<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\FormulaireFormType;
use App\Service\Cart\CartService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'app_checkout')]
    public function index(ManagerRegistry $doctrine, CartService $cart, Client $client = null,
        Request $request): Response
    { 
        $client = new Client();
        // création de formulaire
        $form = $this -> createForm(FormulaireFormType::class, $client);
        
        // cacher les inputs de service s'il n'y a pas de produit de cette famille
        $servExists = false;
        $catExists = false;
        $items = $cart->getFullCart();
        if($items){
            foreach($items['produits'] as $item){
                if( $item['produit']->getService()){
                    $servExists = true;
                }
            }
            // cacher les inputs de service s'il n'y a pas de produit de cette famille
            if(!$servExists){
                $form->remove('dateRetraitPA');
                $form->remove('tempsRetraitPA');
            }
            foreach($items['produits'] as $item){
                if( $item['produit']->getCategorie()){
                    $catExists = true;
                }
            }
            // cacher les inputs de catégorie s'il n'y a pas de produit de cette famille
            if(!$catExists){
                $form->remove('dateRetrait');
                $form->remove('tempsRetrait');
            }
        }
        
        $isSubmitted = false;
        $clientId= null;
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            // si la civilité n'est pas séléctionnée
            if($request->request->get('civilite') === null){
                
                return $this->render('checkout/index.html.twig', [
                    'items' => $cart->getFullCart(),
                    'formAddClient' => $form->createView(),
                    'isSubmitted' => $isSubmitted,
                    'clientId' => $clientId,
                    'civiliteError' =>  true
                ]);
            }else{
                // récupération des données de formulaire 
                $client = $form->getData();
                
                $civilite=$request->request->get('civilite');
                $client->setCivilite($civilite);
                
                // envoie des données vers la BD
                $entityManager = $doctrine->getManager();
                $entityManager->persist($client);
                $entityManager->flush();
                
                $isSubmitted = true;
                // pour envoyer dans la method stripe-checkout
                $clientId = $client -> getId();
            }
        }

        if($cart->getFullCart()){

            return $this->render('checkout/index.html.twig', [
                'items' => $cart->getFullCart(),
                'servExists' => $servExists,
                'catExists' => $catExists,
                'formAddClient' => $form,
                'isSubmitted' => $isSubmitted,
                'clientId' => $clientId,
                'civiliteError' => false, 
            ]);

        }else{
            return $this->redirectToRoute('index_panier');
        }
    }
}
