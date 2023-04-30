<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlushCacheController extends AbstractController
{
    #[Route('/jkhdifushidf15dSER5/flush-cache', name: 'app_flush_cache')]
    public function index(): Response
    {
        opcache_reset();

        return $this->redirectToRoute('app_home');
    }
}
