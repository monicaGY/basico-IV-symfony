<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
   
    #[Route('/', name: 'home')]
    public function index(): JsonResponse
    {
        return $this->json([
            'mensaje' => 'Acceso restringido',
        ], 200);
    }
}
