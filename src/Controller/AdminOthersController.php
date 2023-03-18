<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminOthersController extends AbstractController
{
    
    #[Route('/admin/6w3bocMghDiz4Xvckg6ijiT3kKfqdQl8L3VqNRJQ/1J1P', name: 'app_admin_1J1P')]
    #[IsGranted('ROLE_ADMIN')]
    public function show1J1P(): Response
    {
        return $this->render('administration/admin_others/admin-1J1P.html.twig', []);
    }

    #[Route('/admin/6w3bocMghDiz4Xvckg6ijiT3kKfqdQl8L3VqNRJQ/evenements', name: 'app_admin_events')]
    #[IsGranted('ROLE_ADMIN')]
    public function showEvenements(): Response
    {
        return $this->render('administration/admin_others/admin-evenements.html.twig', []);
    }
}
