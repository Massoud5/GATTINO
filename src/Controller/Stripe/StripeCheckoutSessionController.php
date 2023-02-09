<?php

namespace App\Controller\Stripe;


use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Service\Cart\CartService;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Commande\CommandeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeCheckoutSessionController extends AbstractController
{
    #[Route('/create-checkout-session/{clientId}', name: 'app_checkout_session')]
    public function index(
        EntityManagerInterface $entityManager, CartService $cart, CommandeService $commandeService, 
        ClientRepository $clientRepository, Request $request
        ): Response
    { 
        if (!$cart){
            return $this->redirectToRoute('app_home');
        }

        // retrouver le client grâce à l'id récupéré par la route
        $clientId = $request->attributes->get('clientId');
        $client = $clientRepository->findOneById($clientId);

        // pour envoyer les éléments du panier à Stripe
        $line_items = $commandeService -> getLineItems($cart);

        // pour créer la commande dans la base de données
        $commande = $commandeService -> createCommande($cart, $clientId);
        
        // clé secrète de test donnée par stripe
        Stripe::setApiKey('sk_test_mKp22DMRYnK4YrgVFJyAYFWJ');
        
        //! A changer avant de mettre en production
        $DOMAIN_NAME = 'http://localhost:8000';
        
        $checkout_session = Session::create([
            'customer_email' => $client->getEmail(), 
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => $DOMAIN_NAME . '/stripe-succes-de-paiement/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $DOMAIN_NAME . '/stripe/annule/{CHECKOUT_SESSION_ID}',
        ]);
        
        $commande ->setStripeSessionId($checkout_session->id);
        $entityManager->flush();
        
        return $this->json(['id' => $checkout_session->id]);
        
    }
}

