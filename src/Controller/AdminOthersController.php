<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminOthersController extends AbstractController
{
    
    #[Route('/admin/1J1P', name: 'app_admin_1J1P')]
    public function show1J1P(): Response
    {
        return $this->render('administration/admin_others/admin-1J1P.html.twig', []);
    }

    #[Route('/admin/evenements', name: 'app_admin_events')]
    public function showEvenements(): Response
    {
        return $this->render('administration/admin_others/admin-evenements.html.twig', []);
    }
}
