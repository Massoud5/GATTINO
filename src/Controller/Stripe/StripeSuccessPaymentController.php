<?php

namespace App\Controller\Stripe;

use App\Entity\Commande;
use App\Service\Pdf\PdfService;
use App\Service\Cart\CartService;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeSuccessPaymentController extends AbstractController
{
    #[Route('/stripe-succes-de-paiement/{stripeSessionId}', name: 'stripe_success_payment')]
    public function index(Commande $commande, EntityManagerInterface $entityManager, CartService $cart, CommandeRepository $commandeRepository): Response
    {
        // la commande est automatiquement trouvée par stripeSessionId car il n'y a qu'un seul paramètre dans la methode qui est une entité.
        // dd($commande);
        if(!$commande || !$commande->getClient()){
            return $this->redirectToRoute('app_home');
        }

        // numéro de commande payé
        $latestCommandePaid = $commandeRepository->findOneNCommandePaid();
        if($latestCommandePaid){
            $latestnCommandePaid = $latestCommandePaid[0] -> getNCommandePaid();
        }else{
            $latestnCommandePaid = 0;
        }

        if(!$commande->isIsPaid()){
            $commande->setIsPaid(true);
            $commande->setNCommandePaid($latestnCommandePaid+1);
            $commande->setIsRetrieved(false);
            $entityManager->flush();
            
            $cart->deleteAll();
            // un mail contenant la commande payée est automatiquement envoyé au mail du client
        }

        // redirection vers la création de facture
        if (isset($_POST['factureSubmit'])){
            return $this->redirectToRoute('facture_pdf', [
                'id' => $commande->getId()
            ]);
        }

        return $this->render('stripe/stripe_success_payment/index.html.twig', [
            'items' => $cart->getFullCart(),
            'commande' => $commande
        ]);
    }

    #[Route('/facture/pdf/sd4545fzeE35efdfdfefc54D4545SGDFe/{id}', name:'facture_pdf')]
    public function showFactureFile(PdfService $pdf, Commande $commande){

        $html = $this->render('pdf/pdf.html.twig',['commande' => $commande]);
        return $pdf->showPdfFile($html);
    }
}
