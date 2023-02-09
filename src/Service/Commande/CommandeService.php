<?php

namespace App\Service\Commande;

use DateTime;
use App\Entity\Commande;
use App\Entity\CommandeProduit;
use App\Service\Cart\CartService;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;


Class CommandeService 
{
    protected $entityManager;
    protected $clientRepository;

    public function __construct(EntityManagerInterface $entityManager, ClientRepository $clientRepository) {
        $this->entityManager = $entityManager;
        $this->clientRepository = $clientRepository;
    }

    public function getLineItems(CartService $cart){
        $cartItems = $cart -> getFullCart();
        $line_items = [];

        // dd($cartItems);
        
        foreach ($cartItems['produits'] as $item){
            
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $item['produit']->getPrixUnitaire()* 100,
                    'product_data' => [
                            'name' => $item['produit']->getNomProduit(),
                        ]
                ],
                'quantity' => $item['quantity'],
                // 'tax' => $item['produit']->getTaxe() * 100
            ];
        }

        return $line_items;
    }

    public function createCommande(CartService $cart, $clientId){
        $client = $this->clientRepository->findOneById($clientId);
        $commande = new Commande();

        date_default_timezone_set('Europe/Paris');

        $ht = $cart->getFullCart()['data']["totalHT"];
        $tva = $cart->getFullCart()['data']["totalTaxe"];
        $ttc = $cart->getFullCart()['data']["totalTTC"];

        $commande ->setDateCommande(new \DateTime())
                  ->setHt($ht)
                  ->setTva($tva)
                  ->setTtc($ttc)
                  ->setNCommandePaid(0)
                  ->setClient($client)
                  ->setStripeSessionId("session_id")
                  ->setIsPaid(false);

        if($client->getDateRetrait()){
            $commande   ->setDateRetrait($client->getDateRetrait())
                        ->setTempsRetrait($client->getTempsRetrait());
        }
        if($client->getDateRetraitPA()){
            $commande   ->setDateRetraitPA($client->getDateRetraitPA())
                        ->setTempsRetraitPA($client->getTempsRetraitPA());
        }
        $this->entityManager->persist($commande);
        
        $cartItems = $cart -> getFullCart();
        foreach ($cartItems['produits'] as $item){
            
            $commandeProduit = new CommandeProduit();
            $produit = $item['produit'];
            $quantity = $item['quantity'];
            $commandeProduit->setProduit($produit)
                            ->setQuantite($quantity)
                            ->setCommande($commande);

            $this->entityManager->persist($commandeProduit);
        }

        $this->entityManager->flush();

        return $commande;
    }
}