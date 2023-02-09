<?php

namespace App\Controller\Stripe;

use App\Repository\ClientRepository;
use App\Repository\CommandeProduitRepository;
use App\Service\Cart\CartService;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeCancelController extends AbstractController
{
    #[Route('/stripe/annule/{stripeSessionId}', name: 'app_stripe_cancel')]
    public function index(Request $request, CommandeProduitRepository $commandeProdRepo,
    CommandeRepository $commandeRepository, ClientRepository $clientRepository): Response
    {
        $idSession = $request->attributes->get('stripeSessionId');

        $commande = $commandeRepository->findOneByStripeSessionId($idSession);
        if ($commande){

            $commandeProduits = $commande->getCommandeProduits();
            $client = $commande->getClient();
            
            // effacer les données enregisrés dans db
            foreach($commandeProduits as $commandeProduit){
                $commandeProdRepo->remove($commandeProduit, true);
            }
            $commandeRepository->remove($commande, true);
            $clientRepository->remove($client, true);
        } 
        

        return $this->render('stripe/stripe_cancel/index.html.twig', []);
    }

    #[Route('/stripe/cancel/annuler-achat', name: 'app_cancel_shopping')]
    public function cancelShopping(CartService $cart): Response
    {
        $cart->deleteAll(); 
          
        return $this->redirectToRoute('app_home');
    }

    #[Route('/stripe/cancel/modifier-achat', name: 'app_modify_shopping')]
    public function modifyShopping(): Response
    {
        return $this->redirectToRoute('app_home');
    }
}
